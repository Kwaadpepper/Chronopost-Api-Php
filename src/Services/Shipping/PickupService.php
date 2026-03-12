<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Services\Shipping;

use ChronopostShipping\ClassMap;
use ChronopostShipping\ServiceType\Annuler;
use ChronopostShipping\ServiceType\Creer;
use ChronopostShipping\ServiceType\Faisabilite;
use ChronopostShipping\ServiceType\Rechercher;
use ChronopostShipping\StructType\AdresseDestinataire;
use ChronopostShipping\StructType\AdresseEnlevementV3;
use ChronopostShipping\StructType\AnnulerEnlevements;
use ChronopostShipping\StructType\CreerEnlevementEurope;
use ChronopostShipping\StructType\CreerEnlevementNational;
use ChronopostShipping\StructType\DestinataireDpd;
use ChronopostShipping\StructType\DestinatairesDpd;
use ChronopostShipping\StructType\DonneurDOrdre;
use ChronopostShipping\StructType\FaisabiliteESD as FaisabiliteESDRequest;
use ChronopostShipping\StructType\HeaderValue;
use ChronopostShipping\StructType\InfoClient;
use ChronopostShipping\StructType\Options;
use ChronopostShipping\StructType\Particularites;
use ChronopostShipping\StructType\ParticularitesColisDpd;
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
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\DpdRecipient;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\DpdRecipients;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\EsdParticularities;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\OrderGiver;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\PickupAddress;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\PickupHeader;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\PickupOptions;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\PickupShipper;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PickupSearchCriteria;
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
        #[\SensitiveParameter] private AccountNumber $accountNumber,
        #[\SensitiveParameter] private Password $password,
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
        PickupShipper $shipper,
        string $retrievalDateTime,
        string $closingDateTime,
    ): PickupFeasibility {
        $result = $this->faisabiliteService->faisabiliteESD(new FaisabiliteESDRequest(
            shipperValue: $this->mapShipper($shipper),
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
        PickupSearchCriteria $criteria,
    ): PickupConstraints {
        $result = $this->rechercherService->rechercherContraintesEnlevementV2(
            new RechercherContraintesEnlevementV2(
                country: $criteria->getPostCode()->getCountryDelivery()->getCode(),
                zipCode: $criteria->getPostCode()->getPostCode(),
                city: $criteria->getCity(),
                account: $this->accountNumber->getAccountNumber(),
                password: $this->password->getPassword(),
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
        PickupHeader $header,
        string $datePassage,
        string $datePassageFermeture,
        OrderGiver $orderGiver,
        PickupAddress $pickupAddress,
        ?EsdParticularities $esdParticularities = null,
        ?string $referenceEsdClient = null,
        ?string $contenu = null,
        ?PickupOptions $options = null,
        ?string $locale = null,
    ): PickupCreationResult {
        $result = $this->creerService->creerEnlevementNational(new CreerEnlevementNational(
            headerValue: $this->mapHeader($header),
            password: $this->password->getPassword(),
            datePassage: $datePassage,
            datePassageFermeture: $datePassageFermeture,
            donneurDOrdre: $this->mapOrderGiver($orderGiver),
            adresseEnlevement: $this->mapPickupAddress($pickupAddress),
            particulartiesEsd: $esdParticularities !== null ? $this->mapEsdParticularities($esdParticularities) : null,
            referenceEsdClient: $referenceEsdClient,
            contenu: $contenu,
            options: $options !== null ? $this->mapOptions($options) : null,
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
        PickupHeader $header,
        string $datePassage,
        OrderGiver $orderGiver,
        PickupAddress $pickupAddress,
        ?DpdRecipients $dpdRecipients = null,
        ?string $locale = null,
    ): PickupCreationResult {
        $result = $this->creerService->creerEnlevementEurope(new CreerEnlevementEurope(
            headerValue: $this->mapHeader($header),
            password: $this->password->getPassword(),
            datePassage: $datePassage,
            donneurDOrdre: $this->mapOrderGiver($orderGiver),
            adresseEnlevement: $this->mapPickupAddress($pickupAddress),
            destinatairesEsd: $dpdRecipients !== null ? $this->mapDpdRecipients($dpdRecipients) : null,
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
        array $esdNumbers,
        ?string $locale = null,
    ): CancelPickupResult {
        $result = $this->annulerService->annulerEnlevements(new AnnulerEnlevements(
            accountNumber: $this->accountNumber->getAccountNumber(),
            password: $this->password->getPassword(),
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

    private function mapShipper(PickupShipper $shipper): ShipperValue
    {
        return new ShipperValue(
            shipperAdress1: $shipper->getAddress1(),
            shipperAdress2: $shipper->getAddress2(),
            shipperCity: $shipper->getCity(),
            shipperCivility: $shipper->getCivility()?->value,
            shipperContactName: $shipper->getContactName()?->getValue(),
            shipperCountry: $shipper->getCountry(),
            shipperCountryName: $shipper->getCountryName(),
            shipperEmail: $shipper->getEmail()?->getValue(),
            shipperMobilePhone: $shipper->getMobilePhone(),
            shipperName: $shipper->getName(),
            shipperName2: $shipper->getName2(),
            shipperPhone: $shipper->getPhone(),
            shipperPreAlert: $shipper->getPreAlert(),
            shipperZipCode: $shipper->getZipCode(),
        );
    }

    private function mapHeader(PickupHeader $header): HeaderValue
    {
        return new HeaderValue(
            accountNumber: $header->getAccountNumber(),
            idEmit: $header->getIdEmit(),
            identWebPro: $header->getIdentWebPro(),
            subAccount: $header->getSubAccount(),
        );
    }

    private function mapOrderGiver(OrderGiver $og): DonneurDOrdre
    {
        return new DonneurDOrdre(
            autreTelephone: $og->getOtherPhone(),
            batiment: $og->getBuilding(),
            codeCivilite: $og->getCivility()?->value,
            codeNaf: $og->getNafCode(),
            codePays: $og->getCountryCode(),
            codePostal: $og->getPostalCode(),
            eMail: $og->getEmail()?->getValue(),
            fax: $og->getFax(),
            lieuDit: $og->getHamlet(),
            nom: $og->getLastName()?->getValue(),
            prenom: $og->getFirstName()?->getValue(),
            raisonSociale: $og->getCompanyName(),
            service: $og->getService(),
            telephone: $og->getPhone(),
            ville: $og->getCity(),
            voie: $og->getAddress(),
        );
    }

    private function mapPickupAddress(PickupAddress $addr): AdresseEnlevementV3
    {
        $v3 = new AdresseEnlevementV3(
            refExpediteur: $addr->getSenderReference()?->getValue(),
        );
        // AdresseEnlevementV2 parent field
        $v3->setEmail($addr->getEmail()?->getValue());
        // AdresseEnlevement base fields
        $v3->setCodeCivilite($addr->getCivility()?->value);
        $v3->setCodePays($addr->getCountryCode());
        $v3->setCodePorte($addr->getDoorCode());
        $v3->setCodePostal($addr->getPostalCode());
        $v3->setLieuDit($addr->getHamlet());
        $v3->setNom($addr->getLastName()?->getValue());
        $v3->setNomPersonneARencontrer($addr->getContactName()?->getValue());
        $v3->setNumeroVoie($addr->getStreetNumber());
        $v3->setPorteAPorte($addr->getDoorToDoor());
        $v3->setPrenom($addr->getFirstName()?->getValue());
        $v3->setRaisonSociale($addr->getCompanyName());
        $v3->setResidenceBatimentEtage($addr->getBuildingFloor());
        $v3->setServiceDirection($addr->getServiceDirection());
        $v3->setTelephone($addr->getPhone());
        $v3->setVille($addr->getCity());

        return $v3;
    }

    private function mapEsdParticularities(EsdParticularities $esd): ParticularitesEsd
    {
        return new ParticularitesEsd(
            etudeDeFaisabilite: $esd->getFeasibilityStudy(),
            grosVolume: $esd->getBulkyVolume(),
            hauteur: $esd->getHeight(),
            instructionsParticulieres: $esd->getSpecialInstructions(),
            largeur: $esd->getWidth(),
            listeColisAnnonces: $esd->getAnnouncedParcels(),
            longueur: $esd->getLength(),
            nombreEnvois: $esd->getShipmentCount(),
            poids: $esd->getWeight(),
            volume: $esd->getVolume(),
        );
    }

    private function mapOptions(PickupOptions $opts): Options
    {
        return new Options(
            aviserSurRealisation: $opts->getNotifyOnCompletion(),
            chezUnTiers: $opts->getAtThirdParty(),
            envoyerLtParMail: $opts->getSendLtByEmail(),
            lTaImprimerParChronopost: $opts->getLtPrintedByChronopost(),
        );
    }

    private function mapDpdRecipients(DpdRecipients $recipients): DestinatairesDpd
    {
        $mapped = array_map($this->mapDpdRecipient(...), $recipients->getRecipients());

        return new DestinatairesDpd(destinataireDpd: $mapped);
    }

    private function mapDpdRecipient(DpdRecipient $r): DestinataireDpd
    {
        $addr = $r->getAddress();
        $info = $r->getClientInfo();
        $part = $r->getParticularities();

        return new DestinataireDpd(
            adresseDestinataire: $addr !== null ? new AdresseDestinataire(
                adresse: $addr->getAddress(),
                adresseSuite: $addr->getAddressLine2(),
                codePays: $addr->getCountryCode(),
                codePostal: $addr->getPostalCode(),
                digicode: $addr->getDigicode(),
                etage: $addr->getFloor(),
                mail: $addr->getEmail()?->getValue(),
                nom: $addr->getLastName()?->getValue(),
                poids: $addr->getWeight(),
                prenom: $addr->getFirstName()?->getValue(),
                raisonSociale: $addr->getCompanyName(),
                referenceDestinataire: $addr->getRecipientReference(),
                telephone: $addr->getPhone(),
                ville: $addr->getCity(),
            ) : null,
            infoClient: $info !== null ? new InfoClient(
                contenu: $info->getContent(),
                devise: $info->getCurrency(),
                montant: $info->getAmount(),
                refEsdClient: $info->getClientEsdRef(),
                service: $info->getService(),
            ) : null,
            particularites: $part !== null ? new Particularites(
                hauteur: $part->getHeight(),
                instructionsParticulieres: $part->getSpecialInstructions(),
                largeur: $part->getWidth(),
                longueur: $part->getLength(),
                nombreEnvois: $part->getShipmentCount(),
                poids: $part->getWeight(),
            ) : null,
            particularitesColisDpd: $r->getInsuredValue() !== null ? new ParticularitesColisDpd(
                valeurAssuree: $r->getInsuredValue(),
            ) : null,
        );
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
