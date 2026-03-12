<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Services\Shipping;

use ChronopostShipping\ClassMap;
use ChronopostShipping\ServiceType\Annuler;
use ChronopostShipping\ServiceType\Creer;
use ChronopostShipping\ServiceType\Faisabilite;
use ChronopostShipping\ServiceType\Rechercher;
use ChronopostShipping\StructType\AdresseEnlevementV3;
use ChronopostShipping\StructType\AnnulerEnlevements;
use ChronopostShipping\StructType\CreerEnlevementEurope;
use ChronopostShipping\StructType\CreerEnlevementNational;
use ChronopostShipping\StructType\DestinatairesDpd;
use ChronopostShipping\StructType\DonneurDOrdre;
use ChronopostShipping\StructType\FaisabiliteESD as FaisabiliteESDRequest;
use ChronopostShipping\StructType\HeaderValue;
use ChronopostShipping\StructType\Options;
use ChronopostShipping\StructType\ParticularitesEsd;
use ChronopostShipping\StructType\RechercherContraintesEnlevementV2;
use ChronopostShipping\StructType\ShipperValue;
use Kwaadpepper\ChronopostApiPhp\Contracts\PickupServiceInterface;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\CancelPickupResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\PickupConstraints;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\PickupCreationResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\PickupFeasibility;
use Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError;
use Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\PickupException;
use Kwaadpepper\ChronopostApiPhp\Factory\CancelPickupResultFactory;
use Kwaadpepper\ChronopostApiPhp\Factory\PickupConstraintsFactory;
use Kwaadpepper\ChronopostApiPhp\Factory\PickupCreationResultFactory;
use Kwaadpepper\ChronopostApiPhp\Factory\PickupFeasibilityFactory;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;
use WsdlToPhp\PackageBase\SoapClientInterface;

class PickupService implements PickupServiceInterface
{
    private Faisabilite $faisabiliteService;
    private Rechercher $rechercherService;
    private Creer $creerService;
    private Annuler $annulerService;

    protected static string $serviceUrl = 'https://ws.chronopost.fr/shipping-cxf/ShippingServiceWS?wsdl';

    /**
     * @param array<string, mixed>                              $soapOptions
     * @param \ChronopostShipping\ServiceType\Faisabilite|null  $faisabiliteService
     * @param \ChronopostShipping\ServiceType\Rechercher|null   $rechercherService
     * @param \ChronopostShipping\ServiceType\Creer|null        $creerService
     * @param \ChronopostShipping\ServiceType\Annuler|null      $annulerService
     */
    public function __construct(
        array $soapOptions = [],
        ?Faisabilite $faisabiliteService = null,
        ?Rechercher $rechercherService = null,
        ?Creer $creerService = null,
        ?Annuler $annulerService = null,
    ) {
        if (
            $faisabiliteService !== null && $rechercherService !== null
            && $creerService !== null && $annulerService !== null
        ) {
            $this->faisabiliteService = $faisabiliteService;
            $this->rechercherService = $rechercherService;
            $this->creerService = $creerService;
            $this->annulerService = $annulerService;
            return;
        }

        $soapOptions = array_merge(
            $soapOptions,
            [
                SoapClientInterface::WSDL_URL => static::$serviceUrl,
                SoapClientInterface::WSDL_CLASSMAP => ClassMap::get(),
            ],
        );

        $this->faisabiliteService = $faisabiliteService ?? new Faisabilite($soapOptions);
        $this->rechercherService = $rechercherService ?? new Rechercher($soapOptions);
        $this->creerService = $creerService ?? new Creer($soapOptions);
        $this->annulerService = $annulerService ?? new Annuler($soapOptions);
    }

    public function checkFeasibility(
        ShipperValue $shipperValue,
        string $retrievalDateTime,
        string $closingDateTime,
    ): PickupFeasibility {
        $result = $this->faisabiliteService->faisabiliteESD(new FaisabiliteESDRequest(
            shipperValue: $shipperValue,
            retrievalDateTime: $retrievalDateTime,
            closingDateTime: $closingDateTime,
        ));

        $return = $this->extractReturnOrThrow(
            $result,
            Faisabilite::class . '::faisabiliteESD',
            'Failed to get result from faisabiliteESD service, null response',
        );

        /** @var \ChronopostShipping\StructType\ResultFaisabiliteESD $return */
        return (new PickupFeasibilityFactory())->create($return);
    }

    public function searchConstraints(
        AccountNumber $accountNumber,
        Password $password,
        string $country,
        string $zipCode,
        string $city,
    ): PickupConstraints {
        $result = $this->rechercherService->rechercherContraintesEnlevementV2(
            new RechercherContraintesEnlevementV2(
                country: $country,
                zipCode: $zipCode,
                city: $city,
                account: $accountNumber->getAccountNumber(),
                password: $password->getPassword(),
            ),
        );

        $return = $this->extractReturnOrThrow(
            $result,
            Rechercher::class . '::rechercherContraintesEnlevementV2',
            'Failed to get result from rechercherContraintesEnlevementV2 service, null response',
        );

        /** @var \ChronopostShipping\StructType\EsdResultContraintesAgenceValue $return */
        $this->assertNoPickupError($return->getCodeErreur(), $return->getLibelleErreur());

        return (new PickupConstraintsFactory())->create($return);
    }

    public function createNationalPickup(
        AccountNumber $accountNumber,
        Password $password,
        HeaderValue $headerValue,
        string $datePassage,
        string $datePassageFermeture,
        DonneurDOrdre $donneurDOrdre,
        AdresseEnlevementV3 $adresseEnlevement,
        ?ParticularitesEsd $particularitesEsd = null,
        ?string $referenceEsdClient = null,
        ?string $contenu = null,
        ?Options $options = null,
        ?string $locale = null,
    ): PickupCreationResult {
        $result = $this->creerService->creerEnlevementNational(new CreerEnlevementNational(
            headerValue: $headerValue,
            password: $password->getPassword(),
            datePassage: $datePassage,
            datePassageFermeture: $datePassageFermeture,
            donneurDOrdre: $donneurDOrdre,
            adresseEnlevement: $adresseEnlevement,
            particulartiesEsd: $particularitesEsd,
            referenceEsdClient: $referenceEsdClient,
            contenu: $contenu,
            options: $options,
            locale: $locale,
        ));

        $return = $this->extractReturnOrThrow(
            $result,
            Creer::class . '::creerEnlevementNational',
            'Failed to get result from creerEnlevementNational service, null response',
        );

        /** @var \ChronopostShipping\StructType\ResultEnlevementNational $return */
        $this->assertNoPickupError($return->getCodeErreur(), $return->getLibelleErreur());

        return (new PickupCreationResultFactory())->create($return);
    }

    public function createEuropeanPickup(
        AccountNumber $accountNumber,
        Password $password,
        HeaderValue $headerValue,
        string $datePassage,
        DonneurDOrdre $donneurDOrdre,
        AdresseEnlevementV3 $adresseEnlevement,
        ?DestinatairesDpd $destinatairesEsd = null,
        ?string $locale = null,
    ): PickupCreationResult {
        $result = $this->creerService->creerEnlevementEurope(new CreerEnlevementEurope(
            headerValue: $headerValue,
            password: $password->getPassword(),
            datePassage: $datePassage,
            donneurDOrdre: $donneurDOrdre,
            adresseEnlevement: $adresseEnlevement,
            destinatairesEsd: $destinatairesEsd,
            locale: $locale,
        ));

        $return = $this->extractReturnOrThrow(
            $result,
            Creer::class . '::creerEnlevementEurope',
            'Failed to get result from creerEnlevementEurope service, null response',
        );

        /** @var \ChronopostShipping\StructType\ResultPickupOrCollectionRequest $return */
        $this->assertNoPickupError($return->getCodeErreur(), $return->getLibelleErreur());

        return (new PickupCreationResultFactory())->create($return);
    }

    /**
     * @param string[] $esdNumbers
     */
    public function cancelPickups(
        AccountNumber $accountNumber,
        Password $password,
        array $esdNumbers,
        ?string $locale = null,
    ): CancelPickupResult {
        $result = $this->annulerService->annulerEnlevements(new AnnulerEnlevements(
            accountNumber: $accountNumber->getAccountNumber(),
            password: $password->getPassword(),
            locale: $locale,
            esdNumber: $esdNumbers,
        ));

        $return = $this->extractReturnOrThrow(
            $result,
            Annuler::class . '::annulerEnlevements',
            'Failed to get result from annulerEnlevements service, null response',
        );

        /** @var \ChronopostShipping\StructType\ResultAnnulerEnlevement $return */
        $this->assertNoPickupError($return->getCodeErreur(), $return->getErrorMessage());

        return (new CancelPickupResultFactory())->create($return);
    }

    private function extractReturnOrThrow(bool|object $result, string $method, string $nullMessage): object
    {
        if ($result === false) {
            $serviceWithError = $this->resolveServiceForMethod($method);
            $lastError = $serviceWithError->getLastErrorForMethod(methodName: $method);
            throw new ApiError('Failed to call pickup service', $lastError);
        }

        if (!method_exists($result, 'getReturn')) {
            throw new ApiError('Invalid response from pickup service');
        }

        /** @var object|null $return */
        $return = $result->getReturn();

        if ($return === null) {
            throw new ApiError($nullMessage);
        }

        return $return;
    }

    private function assertNoPickupError(?int $errorCode, ?string $errorMessage): void
    {
        if ($errorCode !== null && $errorCode !== 0) {
            throw new PickupException((string) $errorMessage, $errorCode);
        }
    }

    private function resolveServiceForMethod(string $method): Faisabilite|Rechercher|Creer|Annuler
    {
        if (str_contains($method, 'faisabilite')) {
            return $this->faisabiliteService;
        }

        if (str_contains($method, 'rechercher')) {
            return $this->rechercherService;
        }

        if (str_contains($method, 'creer')) {
            return $this->creerService;
        }

        return $this->annulerService;
    }
}
