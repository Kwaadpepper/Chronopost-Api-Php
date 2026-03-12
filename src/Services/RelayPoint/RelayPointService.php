<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Services\RelayPoint;

use ChronopostRelay\ClassMap;
use ChronopostRelay\ServiceType\Recherche;
use ChronopostRelay\StructType\RechercheDetailPointChronopost;
use ChronopostRelay\StructType\RechercheDetailPointChronopostInter;
use ChronopostRelay\StructType\RecherchePointChronopostInter;
use ChronopostRelay\StructType\RecherchePointChronopostParCoordonneesGeographiques;
use ChronopostRelay\StructType\RecherchePointChronopostParId;
use Kwaadpepper\ChronopostApiPhp\Contracts\RelaySearchServiceInterface;
use Kwaadpepper\ChronopostApiPhp\Dto\Relay\RelaySearchResult;
use Kwaadpepper\ChronopostApiPhp\Enums\CountryForChronopost;
use Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError;
use Kwaadpepper\ChronopostApiPhp\Exceptions\Relay\RelaySearchException;
use Kwaadpepper\ChronopostApiPhp\Factory\RelayPointBasicFactory;
use Kwaadpepper\ChronopostApiPhp\Factory\RelayPointSearchResultFactory;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AddressSearch;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Coordinates;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ProductCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayId;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayPointType;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayServiceType;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\WantedShippingDate;
use WsdlToPhp\PackageBase\SoapClientInterface;

/**
 * Le Web Service retourne les Points Relais ouverts dans les 15 jours à venir.
 * Un colis déposé en Point Relais reste en instance 7 jours, puis fera retour à l'expéditeur.
 * Lorsqu'il s'agit d'une consigne, le délai d'instance descend à 3 jours.
 * Tous les gabarits de colis ne sont pas éligibles à être livrés dans les consignes.
 * N'hésitez pas à solliciter votre commercial afin de vous assurer
 * des contraintes avant de proposer une livraison en consigne à vos clients.
 */
class RelayPointService implements RelaySearchServiceInterface
{
    /**
     * Soap tracking service
     *
     * @var \ChronopostRelay\ServiceType\Recherche
     */
    private Recherche $searchService;

    /**
     * Tracking service soap url
     *
     * @var string
     */
    protected static string $serviceUrl = 'https://ws.chronopost.fr/recherchebt-ws-cxf/PointRelaisServiceWS?wsdl';

    /**
     * Constructor
     *
     * @param array<string, mixed> $soapOptions Additional options for the soap client.
     */
    public function __construct(
        array $soapOptions = [],
        ?Recherche $searchService = null,
    ) {
        if ($searchService !== null) {
            $this->searchService = $searchService;
            return;
        }

        $soapOptions = array_merge(
            $soapOptions,
            [
                SoapClientInterface::WSDL_URL => static::$serviceUrl,
                SoapClientInterface::WSDL_CLASSMAP => ClassMap::get(),
            ],
        );

        $this->searchService = new Recherche($soapOptions);
    }

    /**
     * Find relay points using search criteria.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber            $accountNumber      The account number.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Password                 $password           The password.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\ProductCode              $productCode        The product code.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\AddressSearch            $addressSearch      The address search criteria.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\WantedShippingDate $wantedShippingDate The desired shipping date.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayPointType     $relayPointType     The relay point type (default ANY).
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayServiceType   $relayServiceType   The relay service type (default ANY).
     * @param float|null                                                          $weight             Weight in kg.
     * @param integer|null                                                        $maxResults         Max results (1-25).
     * @param integer|null                                                        $radiusInKm         Search radius (1-50 km).
     * @param string                                                              $language           Language code.
     * @param string                                                              $version            API version.
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Relay\RelaySearchResult
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Relay\RelaySearchException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     */
    public function searchRelayPoint(
        AccountNumber $accountNumber,
        Password $password,
        ProductCode $productCode,
        AddressSearch $addressSearch,
        WantedShippingDate $wantedShippingDate,
        RelayPointType $relayPointType = RelayPointType::ANY,
        RelayServiceType $relayServiceType = RelayServiceType::ANY,
        ?float $weight = null,
        ?int $maxResults = null,
        ?int $radiusInKm = null,
        string $language = 'FR',
        string $version = '2.0',
    ): RelaySearchResult {
        $maxResults = max(1, min(25, $maxResults ?? 25));
        $radiusInKm = max(1, min(50, $radiusInKm ?? 50));
        $parameter  = new RecherchePointChronopostInter(
            $accountNumber->getAccountNumber(),
            $password->getPassword(),
            $addressSearch->address,
            $addressSearch->postalCode->getPostCode(),
            $addressSearch->city,
            $addressSearch->postalCode->getCountryDelivery()->getCode(),
            $relayPointType->value,
            $productCode->getValue(),
            $relayServiceType->value,
            $weight !== null ? (string) $weight : null,
            $wantedShippingDate->date->format('d/m/Y'),
            (string) $maxResults,
            (string) $radiusInKm,
            '1',
            $language,
            $version,
        );

        $result = $this->searchService->recherchePointChronopostInter($parameter);

        if ($result === false) {
            throw new ApiError(
                'Failed to call recherchePointChronopostInter',
                $this->searchService->getLastErrorForMethod(
                    Recherche::class . '::recherchePointChronopostInter',
                ),
            );
        }

        return $this->unwrapPointChrResult($result->getReturn());
    }

    /**
     * Find relay points by GPS coordinates.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber            $accountNumber      The account number.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Password                 $password           The password.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Coordinates              $coordinates        GPS coordinates.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\ProductCode              $productCode        The product code.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\WantedShippingDate $wantedShippingDate The desired shipping date.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayPointType     $relayPointType     The relay point type (default ANY).
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayServiceType   $relayServiceType   The relay service type (default ANY).
     * @param float|null                                                          $weight             Weight in kg.
     * @param integer|null                                                        $maxResults         Max results (1-25).
     * @param integer|null                                                        $radiusInKm         Search radius (1-50 km).
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Relay\RelaySearchResult
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Relay\RelaySearchException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     */
    public function searchRelayPointByCoordinates(
        AccountNumber $accountNumber,
        Password $password,
        Coordinates $coordinates,
        ProductCode $productCode,
        WantedShippingDate $wantedShippingDate,
        RelayPointType $relayPointType = RelayPointType::ANY,
        RelayServiceType $relayServiceType = RelayServiceType::ANY,
        ?float $weight = null,
        ?int $maxResults = null,
        ?int $radiusInKm = null,
    ): RelaySearchResult {
        $maxResults = max(1, min(25, $maxResults ?? 25));
        $radiusInKm = max(1, min(50, $radiusInKm ?? 50));
        $parameter  = new RecherchePointChronopostParCoordonneesGeographiques(
            $accountNumber->getAccountNumber(),
            $password->getPassword(),
            (string) $coordinates->latitude,
            (string) $coordinates->longitude,
            $relayPointType->value,
            $productCode->getValue(),
            $relayServiceType->value,
            $weight !== null ? (string) $weight : null,
            $wantedShippingDate->date->format('d/m/Y'),
            (string) $maxResults,
            (string) $radiusInKm,
            '1',
        );

        $methodName = 'recherchePointChronopostParCoordonneesGeographiques';

        $result = $this->searchService->{$methodName}($parameter);

        if ($result === false) {
            throw new ApiError(
                "Failed to call {$methodName}",
                $this->searchService->getLastErrorForMethod(
                    Recherche::class . '::' . $methodName,
                ),
            );
        }

        return $this->unwrapPointChrResult($result->getReturn());
    }

    /**
     * Find a relay point by its unique identifier.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayId $relayId The relay point identifier.
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Relay\RelayPointBasic[]
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     */
    public function searchRelayPointById(
        RelayId $relayId,
    ): array {
        $parameter = new RecherchePointChronopostParId($relayId->id);

        $result = $this->searchService->recherchePointChronopostParId($parameter);

        if ($result === false) {
            throw new ApiError(
                'Failed to call recherchePointChronopostParId',
                $this->searchService->getLastErrorForMethod(
                    Recherche::class . '::recherchePointChronopostParId',
                ),
            );
        }

        $points = $result->getReturn();
        if ($points === null) {
            return [];
        }

        $factory = new RelayPointBasicFactory();

        return $factory->createFromArray($points);
    }

    /**
     * Get detailed information about a relay point.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber $accountNumber The account number.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Password      $password      The password.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayId $relayId       The relay point identifier.
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Relay\RelaySearchResult
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Relay\RelaySearchException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     */
    public function getRelayPointDetail(
        AccountNumber $accountNumber,
        Password $password,
        RelayId $relayId,
    ): RelaySearchResult {
        $parameter = new RechercheDetailPointChronopost(
            $accountNumber->getAccountNumber(),
            $password->getPassword(),
            $relayId->id,
        );

        $result = $this->searchService->rechercheDetailPointChronopost($parameter);

        if ($result === false) {
            throw new ApiError(
                'Failed to call rechercheDetailPointChronopost',
                $this->searchService->getLastErrorForMethod(
                    Recherche::class . '::rechercheDetailPointChronopost',
                ),
            );
        }

        return $this->unwrapPointChrResult($result->getReturn());
    }

    /**
     * Get detailed information about an international relay point.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber       $accountNumber The account number.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Password            $password      The password.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayId       $relayId       The relay point identifier.
     * @param \Kwaadpepper\ChronopostApiPhp\Enums\CountryForChronopost       $country       The country.
     * @param string                                                         $language      Language code (default 'FR').
     * @param string                                                         $version       API version (default '2.0').
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Relay\RelaySearchResult
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Relay\RelaySearchException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     */
    public function getInternationalRelayPointDetail(
        AccountNumber $accountNumber,
        Password $password,
        RelayId $relayId,
        CountryForChronopost $country,
        string $language = 'FR',
        string $version = '2.0',
    ): RelaySearchResult {
        $parameter = new RechercheDetailPointChronopostInter(
            $accountNumber->getAccountNumber(),
            $password->getPassword(),
            $relayId->id,
            $country->getCode(),
            $language,
            $version,
        );

        $result = $this->searchService->rechercheDetailPointChronopostInter($parameter);

        if ($result === false) {
            throw new ApiError(
                'Failed to call rechercheDetailPointChronopostInter',
                $this->searchService->getLastErrorForMethod(
                    Recherche::class . '::rechercheDetailPointChronopostInter',
                ),
            );
        }

        return $this->unwrapPointChrResult($result->getReturn());
    }

    /**
     * Unwrap a PointCHRResult response into a RelaySearchResult.
     *
     * @param \ChronopostRelay\StructType\PointCHRResult|null $response
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Relay\RelaySearchResult
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Relay\RelaySearchException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     */
    private function unwrapPointChrResult(
        ?\ChronopostRelay\StructType\PointCHRResult $response,
    ): RelaySearchResult {
        if ($response === null) {
            throw new ApiError('Failed to get result from search service, null response');
        }

        if ($response->getErrorCode() !== 0) {
            throw new RelaySearchException(
                $response->getErrorMessage(),
                $response->getErrorCode(),
            );
        }

        $factory = new RelayPointSearchResultFactory();

        return $factory->create($response);
    }
}
