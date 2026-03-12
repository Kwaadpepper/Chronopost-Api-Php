<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\Services\Shipping;

use ChronopostShipping\ServiceType\Annuler;
use ChronopostShipping\ServiceType\Creer;
use ChronopostShipping\ServiceType\Faisabilite;
use ChronopostShipping\ServiceType\Rechercher;
use ChronopostShipping\StructType\AdresseEnlevementV3;
use ChronopostShipping\StructType\AnnulerEnlevementsResponse;
use ChronopostShipping\StructType\CreerEnlevementEuropeResponse;
use ChronopostShipping\StructType\CreerEnlevementNationalResponse;
use ChronopostShipping\StructType\DestinatairesDpd;
use ChronopostShipping\StructType\DonneurDOrdre;
use ChronopostShipping\StructType\Entry;
use ChronopostShipping\StructType\EsdContraintesAgence;
use ChronopostShipping\StructType\EsdResultContraintesAgenceValue;
use ChronopostShipping\StructType\FaisabiliteESDResponse;
use ChronopostShipping\StructType\HeaderValue;
use ChronopostShipping\StructType\InfoEnlevement;
use ChronopostShipping\StructType\RechercherContraintesEnlevementV2Response;
use ChronopostShipping\StructType\ResultAnnulerEnlevement;
use ChronopostShipping\StructType\ResultEnlevementNational;
use ChronopostShipping\StructType\ResultFaisabiliteESD;
use ChronopostShipping\StructType\ResultPickupOrCollectionRequest;
use ChronopostShipping\StructType\ShipperValue;
use ChronopostShipping\StructType\Statut;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\CancelPickupResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\PickupConstraints;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\PickupCreationResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\PickupFeasibility;
use Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError;
use Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\PickupException;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;
use Kwaadpepper\ChronopostApiPhp\Services\Shipping\PickupService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PickupServiceTest extends TestCase
{
    private Faisabilite&MockObject $faisabiliteMock;
    private Rechercher&MockObject $rechercherMock;
    private Creer&MockObject $creerMock;
    private Annuler&MockObject $annulerMock;
    private PickupService $service;
    private AccountNumber $accountNumber;
    private Password $password;

    protected function setUp(): void
    {
        $this->faisabiliteMock = $this->createMock(Faisabilite::class);
        $this->rechercherMock = $this->createMock(Rechercher::class);
        $this->creerMock = $this->createMock(Creer::class);
        $this->annulerMock = $this->createMock(Annuler::class);

        $this->accountNumber = new AccountNumber('19869502');
        $this->password = new Password('255562');

        $this->service = new PickupService(
            accountNumber: $this->accountNumber,
            password: $this->password,
            soapOptions: [],
            faisabiliteService: $this->faisabiliteMock,
            rechercherService: $this->rechercherMock,
            creerService: $this->creerMock,
            annulerService: $this->annulerMock,
        );
    }

    // ──── Feasibility ────

    public function testGivenValidShipperWhenCheckFeasibilityThenReturnsFeasible(): void
    {
        // Given
        $result = new ResultFaisabiliteESD(errorCode: 0, errorMessage: '');
        $this->faisabiliteMock
            ->method('faisabiliteESD')
            ->willReturn(new FaisabiliteESDResponse($result));

        // When
        $feasibility = $this->service->checkFeasibility(
            new ShipperValue(),
            '2026-03-15T09:00:00',
            '2026-03-15T18:00:00',
        );

        // Then
        self::assertInstanceOf(PickupFeasibility::class, $feasibility);
        self::assertTrue($feasibility->feasible);
        self::assertSame(0, $feasibility->errorCode);
    }

    public function testGivenInfeasibleWhenCheckFeasibilityThenReturnsFalse(): void
    {
        // Given
        $result = new ResultFaisabiliteESD(errorCode: 9, errorMessage: 'ZIP unknown');
        $this->faisabiliteMock
            ->method('faisabiliteESD')
            ->willReturn(new FaisabiliteESDResponse($result));

        // When
        $feasibility = $this->service->checkFeasibility(
            new ShipperValue(),
            '2026-03-15T09:00:00',
            '2026-03-15T18:00:00',
        );

        // Then
        self::assertFalse($feasibility->feasible);
        self::assertSame(9, $feasibility->errorCode);
        self::assertSame('ZIP unknown', $feasibility->errorMessage);
    }

    public function testGivenSoapFailureWhenCheckFeasibilityThenThrowsApiError(): void
    {
        // Given
        $this->faisabiliteMock
            ->method('faisabiliteESD')
            ->willReturn(false);
        $this->faisabiliteMock
            ->method('getLastErrorForMethod')
            ->willReturn(new \SoapFault('Server', 'Connection failed'));

        // Then
        $this->expectException(ApiError::class);

        // When
        $this->service->checkFeasibility(
            new ShipperValue(),
            '2026-03-15T09:00:00',
            '2026-03-15T18:00:00',
        );
    }

    // ──── Constraints ────

    public function testGivenLocationWhenSearchConstraintsThenReturnsConstraints(): void
    {
        // Given
        $agence = new EsdContraintesAgence(
            codeAgence: 'AG01',
            nomAgence: 'Paris Nord',
            codePays: 'FR',
            codePostal: '75018',
            ville: 'Paris',
            battement: 60,
            hla: '08:00',
            hlp: '18:00',
            hppt: '14:00',
        );
        $result = new EsdResultContraintesAgenceValue(
            codeErreur: 0,
            libelleErreur: '',
            esdContraintesAgence: [$agence],
        );
        $this->rechercherMock
            ->method('rechercherContraintesEnlevementV2')
            ->willReturn(new RechercherContraintesEnlevementV2Response($result));

        // When
        $constraints = $this->service->searchConstraints(
            'FR',
            '75018',
            'Paris',
        );

        // Then
        self::assertInstanceOf(PickupConstraints::class, $constraints);
        self::assertSame(0, $constraints->errorCode);
        self::assertCount(1, $constraints->constraints);
        self::assertSame('AG01', $constraints->constraints[0]->codeAgence);
        self::assertSame('Paris Nord', $constraints->constraints[0]->nomAgence);
        self::assertSame('08:00', $constraints->constraints[0]->hla);
    }

    public function testGivenErrorWhenSearchConstraintsThenThrowsPickupException(): void
    {
        // Given
        $result = new EsdResultContraintesAgenceValue(
            codeErreur: 10,
            libelleErreur: 'Contraintes non trouvées',
        );
        $this->rechercherMock
            ->method('rechercherContraintesEnlevementV2')
            ->willReturn(new RechercherContraintesEnlevementV2Response($result));

        // Then
        $this->expectException(PickupException::class);
        $this->expectExceptionCode(10);

        // When
        $this->service->searchConstraints(
            'FR',
            '99999',
            'Inconnu',
        );
    }

    // ──── Create National Pickup ────

    public function testGivenValidDataWhenCreateNationalPickupThenReturnsResult(): void
    {
        // Given
        $infoEnlevement = new InfoEnlevement(
            numeroUniqueESD: 'ESD123456',
            idEnlevement: 42,
            codeBu: 'BU01',
            codeDepot: 'DEP01',
            codePostal: '75018',
            ville: 'Paris',
            datePassage: '2026-03-15',
        );
        $result = new ResultEnlevementNational(
            codeErreur: 0,
            infoEnlevement: $infoEnlevement,
            libelleErreur: '',
        );
        $this->creerMock
            ->method('creerEnlevementNational')
            ->willReturn(new CreerEnlevementNationalResponse($result));

        // When
        $pickupResult = $this->service->createNationalPickup(
            new HeaderValue(accountNumber: 19869502),
            '2026-03-15T09:00:00',
            '2026-03-15T18:00:00',
            new DonneurDOrdre(),
            new AdresseEnlevementV3(),
        );

        // Then
        self::assertInstanceOf(PickupCreationResult::class, $pickupResult);
        self::assertSame('ESD123456', $pickupResult->numeroUniqueESD);
        self::assertCount(1, $pickupResult->pickupInfos);
        self::assertSame(42, $pickupResult->pickupInfos[0]->idEnlevement);
        self::assertSame('Paris', $pickupResult->pickupInfos[0]->ville);
    }

    public function testGivenErrorWhenCreateNationalPickupThenThrowsPickupException(): void
    {
        // Given
        $result = new ResultEnlevementNational(
            codeErreur: 9,
            libelleErreur: 'Code postal inconnu',
        );
        $this->creerMock
            ->method('creerEnlevementNational')
            ->willReturn(new CreerEnlevementNationalResponse($result));

        // Then
        $this->expectException(PickupException::class);
        $this->expectExceptionCode(9);

        // When
        $this->service->createNationalPickup(
            new HeaderValue(accountNumber: 19869502),
            '2026-03-15T09:00:00',
            '2026-03-15T18:00:00',
            new DonneurDOrdre(),
            new AdresseEnlevementV3(),
        );
    }

    // ──── Create European Pickup ────

    public function testGivenValidDataWhenCreateEuropeanPickupThenReturnsResult(): void
    {
        // Given
        $infoEnlevement = new InfoEnlevement(
            numeroUniqueESD: 'ESDEU789',
            idEnlevement: 99,
            codeBu: 'BU02',
        );
        $result = new ResultPickupOrCollectionRequest(
            codeErreur: 0,
            collectionRequest: true,
            libelleErreur: '',
            infoEnlevements: [$infoEnlevement],
        );
        $this->creerMock
            ->method('creerEnlevementEurope')
            ->willReturn(new CreerEnlevementEuropeResponse($result));

        // When
        $pickupResult = $this->service->createEuropeanPickup(
            new HeaderValue(accountNumber: 19869502),
            '2026-03-20T10:00:00',
            new DonneurDOrdre(),
            new AdresseEnlevementV3(),
            new DestinatairesDpd(),
        );

        // Then
        self::assertInstanceOf(PickupCreationResult::class, $pickupResult);
        self::assertSame('ESDEU789', $pickupResult->numeroUniqueESD);
        self::assertCount(1, $pickupResult->pickupInfos);
        self::assertSame(99, $pickupResult->pickupInfos[0]->idEnlevement);
    }

    // ──── Cancel Pickups ────

    public function testGivenEsdNumbersWhenCancelPickupsThenReturnsResult(): void
    {
        // Given
        $statut = new Statut(entry: [
            new Entry(key: 'ESD123456', value: 'ANNULE'),
            new Entry(key: 'ESD789012', value: 'ANNULE'),
        ]);
        $result = new ResultAnnulerEnlevement(
            codeErreur: 0,
            errorMessage: '',
            statut: $statut,
        );
        $this->annulerMock
            ->method('annulerEnlevements')
            ->willReturn(new AnnulerEnlevementsResponse($result));

        // When
        $cancelResult = $this->service->cancelPickups(
            ['ESD123456', 'ESD789012'],
        );

        // Then
        self::assertInstanceOf(CancelPickupResult::class, $cancelResult);
        self::assertSame(0, $cancelResult->errorCode);
        self::assertCount(2, $cancelResult->statuses);
        self::assertSame('ANNULE', $cancelResult->statuses['ESD123456']);
        self::assertSame('ANNULE', $cancelResult->statuses['ESD789012']);
    }

    public function testGivenInvalidEsdWhenCancelPickupsThenThrowsPickupException(): void
    {
        // Given
        $result = new ResultAnnulerEnlevement(
            codeErreur: 28,
            errorMessage: 'ESD non trouvé',
        );
        $this->annulerMock
            ->method('annulerEnlevements')
            ->willReturn(new AnnulerEnlevementsResponse($result));

        // Then
        $this->expectException(PickupException::class);
        $this->expectExceptionCode(28);

        // When
        $this->service->cancelPickups(
            ['ESD_INVALID'],
        );
    }
}
