<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\Services\RelayPoint;

use ChronopostRelay\ServiceType\Recherche;
use ChronopostRelay\StructType\PointCHR;
use ChronopostRelay\StructType\PointCHRResult;
use ChronopostRelay\StructType\PointChronopost;
use ChronopostRelay\StructType\RechercheDetailPointChronopostInterResponse;
use ChronopostRelay\StructType\RechercheDetailPointChronopostResponse;
use ChronopostRelay\StructType\RecherchePointChronopostInterResponse;
use ChronopostRelay\StructType\RecherchePointChronopostParCoordonneesGeographiquesResponse;
use ChronopostRelay\StructType\RecherchePointChronopostParIdResponse;
use Kwaadpepper\ChronopostApiPhp\Dto\Relay\RelayPointBasic;
use Kwaadpepper\ChronopostApiPhp\Dto\Relay\RelaySearchResult;
use Kwaadpepper\ChronopostApiPhp\Enums\CountryForChronopost;
use Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError;
use Kwaadpepper\ChronopostApiPhp\Exceptions\Relay\RelaySearchException;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AddressSearch;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Coordinates;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ProductCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayId;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayPointType;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\WantedShippingDate;
use Kwaadpepper\ChronopostApiPhp\Services\RelayPoint\RelayPointService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class RelayPointServiceTest extends TestCase
{
    private Recherche&MockObject $rechercheMock;

    private RelayPointService $service;

    private AccountNumber $accountNumber;

    private Password $password;

    protected function setUp(): void
    {
        $this->rechercheMock = $this->createMock(Recherche::class);
        $this->accountNumber = new AccountNumber('19869502');
        $this->password = new Password('255562');

        $this->service = new RelayPointService(
            accountNumber: $this->accountNumber,
            password: $this->password,
            searchService: $this->rechercheMock,
        );
    }

    private function createPointChr(string $id = 'PR001', string $nom = 'Relais Test'): PointCHR
    {
        return new PointCHR(
            false,
            true,
            '1 rue de la Paix',
            '',
            '',
            '250',
            '75001',
            '48.8566',
            '2.3522',
            500,
            $id,
            'RDC',
            'Paris',
            $nom,
            30,
            'A',
            'https://maps.google.com/test',
        );
    }

    /**
     * @param PointCHR[]|null $points
     */
    private function createPointChrResult(
        int $errorCode = 0,
        string $errorMessage = '',
        ?array $points = null,
    ): PointCHRResult {
        if ($points === null) {
            $points = [$this->createPointChr()];
        }

        return new PointCHRResult($errorCode, $errorMessage, 2, 'req-123', $points);
    }

    private function createPointChronopost(
        string $id = 'CP001',
        string $nom = 'Bureau Chrono',
    ): PointChronopost {
        return new PointChronopost(
            '10 avenue des Champs',
            '',
            '',
            '75008',
            '2025-01-15',
            '09:00-12:00',
            '09:00-18:00',
            '09:00-18:00',
            '09:00-18:00',
            '09:00-18:00',
            '09:00-14:00',
            '09:00-18:00',
            '09:00-18:00 / 14:00-18:00',
            $id,
            'Paris',
            $nom,
            'A',
        );
    }

    // ─── searchRelayPoint ───

    public function testGivenValidParamsWhenSearchRelayPointThenReturnsResult(): void
    {
        $soapResponse = new RecherchePointChronopostInterResponse(
            $this->createPointChrResult(),
        );

        $this->rechercheMock
            ->method('recherchePointChronopostInter')
            ->willReturn($soapResponse);

        $result = $this->service->searchRelayPoint(
            new ProductCode('86'),
            new AddressSearch(new PostCode('75001', CountryForChronopost::FRANCE), 'Paris'),
            new WantedShippingDate(new \DateTimeImmutable('+1 day')),
        );

        self::assertInstanceOf(RelaySearchResult::class, $result);
        self::assertCount(1, $result->relayList);
        self::assertSame('PR001', $result->relayList[0]->relayId->id);
    }

    public function testGivenApiErrorWhenSearchRelayPointThenThrowsApiError(): void
    {
        $this->rechercheMock
            ->method('recherchePointChronopostInter')
            ->willReturn(false);
        $this->rechercheMock
            ->method('getLastErrorForMethod')
            ->willReturn(new \SoapFault('Server', 'Connection failed'));

        $this->expectException(ApiError::class);
        $this->service->searchRelayPoint(
            new ProductCode('86'),
            new AddressSearch(new PostCode('75001', CountryForChronopost::FRANCE), 'Paris'),
            new WantedShippingDate(new \DateTimeImmutable('+1 day')),
        );
    }

    public function testGivenErrorCodeWhenSearchRelayPointThenThrowsRelaySearchException(): void
    {
        $soapResponse = new RecherchePointChronopostInterResponse(
            $this->createPointChrResult(errorCode: 301, errorMessage: 'Invalid product code'),
        );

        $this->rechercheMock
            ->method('recherchePointChronopostInter')
            ->willReturn($soapResponse);

        $this->expectException(RelaySearchException::class);
        $this->service->searchRelayPoint(
            new ProductCode('86'),
            new AddressSearch(new PostCode('75001', CountryForChronopost::FRANCE), 'Paris'),
            new WantedShippingDate(new \DateTimeImmutable('+1 day')),
        );
    }

    // ─── searchRelayPointByCoordinates ───

    public function testGivenValidCoordsWhenSearchByCoordinatesThenReturnsResult(): void
    {
        $soapResponse = new RecherchePointChronopostParCoordonneesGeographiquesResponse(
            $this->createPointChrResult(),
        );

        $this->rechercheMock
            ->method('recherchePointChronopostParCoordonneesGeographiques')
            ->willReturn($soapResponse);

        $result = $this->service->searchRelayPointByCoordinates(
            new Coordinates(48.8566, 2.3522),
            new ProductCode('86'),
            new WantedShippingDate(new \DateTimeImmutable('+1 day')),
        );

        self::assertInstanceOf(RelaySearchResult::class, $result);
        self::assertCount(1, $result->relayList);
        self::assertSame('Relais Test', $result->relayList[0]->name);
    }

    public function testGivenApiErrorWhenSearchByCoordinatesThenThrowsApiError(): void
    {
        $this->rechercheMock
            ->method('recherchePointChronopostParCoordonneesGeographiques')
            ->willReturn(false);
        $this->rechercheMock
            ->method('getLastErrorForMethod')
            ->willReturn(new \SoapFault('Server', 'Timeout'));

        $this->expectException(ApiError::class);
        $this->service->searchRelayPointByCoordinates(
            new Coordinates(48.8566, 2.3522),
            new ProductCode('86'),
            new WantedShippingDate(new \DateTimeImmutable('+1 day')),
        );
    }

    public function testGivenErrorCodeWhenSearchByCoordinatesThenThrowsRelaySearchException(): void
    {
        $soapResponse = new RecherchePointChronopostParCoordonneesGeographiquesResponse(
            $this->createPointChrResult(errorCode: 309, errorMessage: 'Invalid coordinates'),
        );

        $this->rechercheMock
            ->method('recherchePointChronopostParCoordonneesGeographiques')
            ->willReturn($soapResponse);

        $this->expectException(RelaySearchException::class);
        $this->service->searchRelayPointByCoordinates(
            new Coordinates(48.8566, 2.3522),
            new ProductCode('86'),
            new WantedShippingDate(new \DateTimeImmutable('+1 day')),
        );
    }

    public function testGivenNullResponseWhenSearchByCoordinatesThenThrowsApiError(): void
    {
        $soapResponse = new RecherchePointChronopostParCoordonneesGeographiquesResponse(null);

        $this->rechercheMock
            ->method('recherchePointChronopostParCoordonneesGeographiques')
            ->willReturn($soapResponse);

        $this->expectException(ApiError::class);
        $this->service->searchRelayPointByCoordinates(
            new Coordinates(48.8566, 2.3522),
            new ProductCode('86'),
            new WantedShippingDate(new \DateTimeImmutable('+1 day')),
        );
    }

    // ─── searchRelayPointById ───

    public function testGivenValidIdWhenSearchByIdThenReturnsBasicPoints(): void
    {
        $points = [$this->createPointChronopost(), $this->createPointChronopost('CP002', 'Autre Bureau')];
        $soapResponse = new RecherchePointChronopostParIdResponse($points);

        $this->rechercheMock
            ->method('recherchePointChronopostParId')
            ->willReturn($soapResponse);

        $result = $this->service->searchRelayPointById(new RelayId('CP001'));

        self::assertCount(2, $result);
        self::assertInstanceOf(RelayPointBasic::class, $result[0]);
        self::assertSame('CP001', $result[0]->relayId->id);
        self::assertSame('Bureau Chrono', $result[0]->name);
        self::assertSame('CP002', $result[1]->relayId->id);
    }

    public function testGivenApiErrorWhenSearchByIdThenThrowsApiError(): void
    {
        $this->rechercheMock
            ->method('recherchePointChronopostParId')
            ->willReturn(false);
        $this->rechercheMock
            ->method('getLastErrorForMethod')
            ->willReturn(new \SoapFault('Server', 'Connection refused'));

        $this->expectException(ApiError::class);
        $this->service->searchRelayPointById(new RelayId('CP001'));
    }

    public function testGivenNullReturnWhenSearchByIdThenReturnsEmptyArray(): void
    {
        $soapResponse = new RecherchePointChronopostParIdResponse(null);

        $this->rechercheMock
            ->method('recherchePointChronopostParId')
            ->willReturn($soapResponse);

        $result = $this->service->searchRelayPointById(new RelayId('UNKNOWN'));

        self::assertSame([], $result);
    }

    public function testGivenRelayPointBasicWhenSearchByIdThenMapsAllFields(): void
    {
        $points = [$this->createPointChronopost()];
        $soapResponse = new RecherchePointChronopostParIdResponse($points);

        $this->rechercheMock
            ->method('recherchePointChronopostParId')
            ->willReturn($soapResponse);

        $result = $this->service->searchRelayPointById(new RelayId('CP001'));

        $point = $result[0];
        self::assertSame('10 avenue des Champs', $point->address1);
        self::assertSame('75008', $point->postcode->getPostCode());
        self::assertSame('Paris', $point->city);
        self::assertSame(RelayPointType::CHRONOPOST_AGENCY, $point->type);
        self::assertSame('2025-01-15', $point->parcelArrivalDate);
        self::assertSame('09:00-18:00', $point->mondayHours);
        self::assertSame('09:00-12:00', $point->sundayHours);
        self::assertSame('09:00-18:00 / 14:00-18:00', $point->formattedOpeningHours);
    }

    // ─── getRelayPointDetail ───

    public function testGivenValidIdWhenGetDetailThenReturnsSearchResult(): void
    {
        $soapResponse = new RechercheDetailPointChronopostResponse(
            $this->createPointChrResult(),
        );

        $this->rechercheMock
            ->method('rechercheDetailPointChronopost')
            ->willReturn($soapResponse);

        $result = $this->service->getRelayPointDetail(
            new RelayId('PR001'),
        );

        self::assertInstanceOf(RelaySearchResult::class, $result);
        self::assertCount(1, $result->relayList);
    }

    public function testGivenApiErrorWhenGetDetailThenThrowsApiError(): void
    {
        $this->rechercheMock
            ->method('rechercheDetailPointChronopost')
            ->willReturn(false);
        $this->rechercheMock
            ->method('getLastErrorForMethod')
            ->willReturn(new \SoapFault('Server', 'Timeout'));

        $this->expectException(ApiError::class);
        $this->service->getRelayPointDetail(
            new RelayId('PR001'),
        );
    }

    public function testGivenErrorCodeWhenGetDetailThenThrowsRelaySearchException(): void
    {
        $soapResponse = new RechercheDetailPointChronopostResponse(
            $this->createPointChrResult(errorCode: 317, errorMessage: 'Point not found'),
        );

        $this->rechercheMock
            ->method('rechercheDetailPointChronopost')
            ->willReturn($soapResponse);

        $this->expectException(RelaySearchException::class);
        $this->service->getRelayPointDetail(
            new RelayId('PR001'),
        );
    }

    // ─── getInternationalRelayPointDetail ───

    public function testGivenValidParamsWhenGetInterDetailThenReturnsSearchResult(): void
    {
        $soapResponse = new RechercheDetailPointChronopostInterResponse(
            $this->createPointChrResult(),
        );

        $this->rechercheMock
            ->method('rechercheDetailPointChronopostInter')
            ->willReturn($soapResponse);

        $result = $this->service->getInternationalRelayPointDetail(
            new RelayId('PR001'),
            CountryForChronopost::BELGIQUE,
        );

        self::assertInstanceOf(RelaySearchResult::class, $result);
        self::assertCount(1, $result->relayList);
    }

    public function testGivenApiErrorWhenGetInterDetailThenThrowsApiError(): void
    {
        $this->rechercheMock
            ->method('rechercheDetailPointChronopostInter')
            ->willReturn(false);
        $this->rechercheMock
            ->method('getLastErrorForMethod')
            ->willReturn(new \SoapFault('Server', 'Error'));

        $this->expectException(ApiError::class);
        $this->service->getInternationalRelayPointDetail(
            new RelayId('PR001'),
            CountryForChronopost::BELGIQUE,
        );
    }

    public function testGivenErrorCodeWhenGetInterDetailThenThrowsRelaySearchException(): void
    {
        $soapResponse = new RechercheDetailPointChronopostInterResponse(
            $this->createPointChrResult(errorCode: 700, errorMessage: 'Country not supported'),
        );

        $this->rechercheMock
            ->method('rechercheDetailPointChronopostInter')
            ->willReturn($soapResponse);

        $this->expectException(RelaySearchException::class);
        $this->service->getInternationalRelayPointDetail(
            new RelayId('PR001'),
            CountryForChronopost::BELGIQUE,
        );
    }

    // ─── ObjectValues Validation ───

    public function testGivenInvalidLatitudeWhenCreatingCoordinatesThenThrows(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Coordinates(91.0, 2.0);
    }

    public function testGivenInvalidLongitudeWhenCreatingCoordinatesThenThrows(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Coordinates(48.0, -181.0);
    }

    public function testGivenEmptyIdWhenCreatingRelayIdThenThrows(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new RelayId('');
    }

    public function testGivenWhitespaceIdWhenCreatingRelayIdThenThrows(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new RelayId('   ');
    }

    public function testGivenValidCoordinatesWhenCreatingThenSucceeds(): void
    {
        $coords = new Coordinates(-90.0, 180.0);
        self::assertSame(-90.0, $coords->latitude);
        self::assertSame(180.0, $coords->longitude);
    }
}
