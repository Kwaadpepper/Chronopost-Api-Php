<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Services\RelayPoint;

use ChronopostRelay\ClassMap;
use ChronopostRelay\ServiceType\Recherche;
use ChronopostRelay\StructType\RecherchePointChronopostInter;
use Kwaadpepper\ChronopostApiPhp\Dto\Relay\RelaySearchResult;
use Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError;
use Kwaadpepper\ChronopostApiPhp\Exceptions\Relay\RelaySearchException;
use Kwaadpepper\ChronopostApiPhp\Factory\RelayPointSearchResultFactory;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AddressSearch;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ProductCode;
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
class RelayPointService
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
     * @param array $soapOptions Additional options for the soap client.
     */
    public function __construct(
        array $soapOptions = []
    ) {
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
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber $accountNumber      The account number for authentication.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Password      $password           The password for authentication.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\ProductCode   $productCode        The product code for the search.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\AddressSearch $addressSearch      The address search criteria.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\WantedShippingDate $wantedShippingDate The desired shipping date.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayPointType $relayPointType    The type of relay point to search for (default is ANY).
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayServiceType $relayServiceType  The type of relay service to search for (default is ANY).
     * @param float|null $weight Optional weight of the package (in kg) for filtering results.
     * @param int|null   $maxResults Optional maximum number of results to return (default is 25, max is 25).
     * @param int|null   $radiusInKm Optional search radius in kilometers (default is 50, max is 50).
     * @param string     $language Optional language code for the response (default is 'FR').
     * @param string     $version Optional version of the API to use (default is '2.0').
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Relay\RelaySearchResult An array of relay points matching the search criteria.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Relay\RelaySearchException If the API returns an error response.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError          If the API call fails or returns an invalid response.
     */
    public function seachRelayPoint(
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
        $parameter = new RecherchePointChronopostInter(
            $accountNumber->getAccountNumber(),
            $password->getPassword(),
            $addressSearch->address,
            $addressSearch->postalCode->getPostCode(),
            $addressSearch->city,
            $addressSearch->postalCode->getCountryDelivery()->getCode(),
            $relayPointType->value,
            $productCode->getValue(),
            $relayServiceType->value,
            $weight !== null ? strval($weight) : null,
            $wantedShippingDate->date->format('d/m/Y'),
            strval($maxResults),
            strval($radiusInKm),
            '1',
            $language,
            $version,
        );

        $result = $this->searchService->recherchePointChronopostInter($parameter);

        if ($result === false) {
            $lastError = $this->searchService->getLastErrorForMethod(methodName: Recherche::class . '::recherchePointChronopostInter');
            throw new ApiError('Failed to call from search service', $lastError);
        }

        $response = $result->getReturn();
        if ($response === null) {
            throw new ApiError('Failed to get result from search service, null response');
        }

        if ($response->getErrorCode() !== 0) {
            $errorMessage = $response->getErrorMessage();
            $errorCode    = $response->getErrorCode();

            throw new RelaySearchException($errorMessage, $errorCode);
        }

        $factory = new RelayPointSearchResultFactory();

        return $factory->create($response);
    }
}
