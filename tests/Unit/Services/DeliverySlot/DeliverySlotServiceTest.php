<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\Services\DeliverySlot;

use ChronopostTimeSlot\ServiceType\Confirm;
use ChronopostTimeSlot\ServiceType\Get;
use ChronopostTimeSlot\ServiceType\Search;
use ChronopostTimeSlot\StructType\ConfirmDeliverySlotV2Response;
use ChronopostTimeSlot\StructType\DeliverySlotResponse;
use ChronopostTimeSlot\StructType\GeocodageResponse;
use ChronopostTimeSlot\StructType\GetAdresseGeocodageResponse;
use ChronopostTimeSlot\StructType\ProductServiceV2;
use ChronopostTimeSlot\StructType\SearchDeliverySlotResponse;
use ChronopostTimeSlot\StructType\ServiceResponseV2;
use ChronopostTimeSlot\StructType\Slot;
use Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError;
use Kwaadpepper\ChronopostApiPhp\Exceptions\DeliverySlot\DeliverySlotException;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;
use Kwaadpepper\ChronopostApiPhp\Services\DeliverySlot\DeliverySlotService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class DeliverySlotServiceTest extends TestCase
{
    private Search&MockObject $searchMock;

    private Confirm&MockObject $confirmMock;

    private Get&MockObject $getMock;

    private DeliverySlotService $service;

    private AccountNumber $accountNumber;

    private Password $password;

    protected function setUp(): void
    {
        $this->searchMock  = $this->createMock(Search::class);
        $this->confirmMock = $this->createMock(Confirm::class);
        $this->getMock     = $this->createMock(Get::class);

        $this->accountNumber = new AccountNumber('19869502');
        $this->password      = new Password('255562');

        $this->service = new DeliverySlotService(
            searchService: $this->searchMock,
            confirmService: $this->confirmMock,
            getService: $this->getMock,
        );
    }

    private function createSlot(
        string $code = 'SLOT001',
        string $date = '2025-01-15',
        int $dayOfWeek = 3,
        int $startHour = 10,
        int $startMinutes = 0,
        int $endHour = 12,
        int $endMinutes = 0,
    ): Slot {
        return new Slot(
            $code,
            $date,
            $dayOfWeek,
            $startHour,
            $startMinutes,
            $endHour,
            $endMinutes,
            'N1',
            'O',
            '0',
            5,
            false,
            1,
            1,
        );
    }

    /**
     * @param \ChronopostTimeSlot\StructType\Slot[]|null $slots
     */
    private function createDeliverySlotResponse(
        int $code = 0,
        string $message = '',
        string $meshCode = 'MESH001',
        string $transactionId = 'TXN001',
        ?array $slots = null,
    ): DeliverySlotResponse {
        $response = new DeliverySlotResponse($meshCode, $slots ?? [$this->createSlot()], $transactionId);
        $response->setCode($code);
        $response->setMessage($message);

        return $response;
    }

    private function createServiceResponseV2(
        int $code = 0,
        string $message = 'OK',
        string $productCode = '2R',
        string $serviceCode = '0',
        string $asCode = 'AS1',
    ): ServiceResponseV2 {
        $productServiceV2 = new ProductServiceV2($asCode);
        $productServiceV2->setProductCode($productCode);
        $productServiceV2->setServiceCode($serviceCode);

        $response = new ServiceResponseV2($productServiceV2);
        $response->setCode($code);
        $response->setMessage($message);

        return $response;
    }

    private function createGeocodageResponse(
        int $code = 0,
        string $message = '',
        float $lat = 48.8566,
        float $lon = 2.3522,
        int $qualite = 1,
    ): GeocodageResponse {
        $response = new GeocodageResponse($lat, $lon, $qualite);
        $response->setCode($code);
        $response->setMessage($message);

        return $response;
    }

    // ── searchDeliverySlots ────────────────────────────────────────────────

    public function testGivenAddressWhenSearchSlotsThenReturnsAvailableSlots(): void
    {
        $deliverySlotResponse = $this->createDeliverySlotResponse();
        $searchResponse       = new SearchDeliverySlotResponse($deliverySlotResponse);

        $this->searchMock
            ->expects($this->once())
            ->method('searchDeliverySlot')
            ->willReturn($searchResponse);

        $result = $this->service->searchDeliverySlots(
            $this->accountNumber,
            $this->password,
            '2R',
            '1 rue de la Paix',
            '75001',
            'Paris',
            'FR',
            '2025-01-15',
            '2025-01-20',
        );

        $this->assertSame('MESH001', $result->meshCode);
        $this->assertSame('TXN001', $result->transactionId);
        $this->assertCount(1, $result->slots);
        $this->assertSame('SLOT001', $result->slots[0]->deliverySlotCode);
        $this->assertSame('2025-01-15', $result->slots[0]->deliveryDate);
        $this->assertSame(10, $result->slots[0]->startHour);
        $this->assertSame(0, $result->slots[0]->startMinutes);
        $this->assertSame(12, $result->slots[0]->endHour);
    }

    public function testGivenNoSlotsWhenSearchThenReturnsEmptyList(): void
    {
        $deliverySlotResponse = $this->createDeliverySlotResponse(slots: []);
        $searchResponse       = new SearchDeliverySlotResponse($deliverySlotResponse);

        $this->searchMock
            ->expects($this->once())
            ->method('searchDeliverySlot')
            ->willReturn($searchResponse);

        $result = $this->service->searchDeliverySlots(
            $this->accountNumber,
            $this->password,
            '2R',
            '1 rue de la Paix',
            '75001',
            'Paris',
            'FR',
            '2025-01-15',
            '2025-01-20',
        );

        $this->assertCount(0, $result->slots);
    }

    public function testGivenMultipleSlotsWhenSearchThenReturnsAll(): void
    {
        $slot1                = $this->createSlot('SLOT001', '2025-01-15', 3, 10, 0, 12, 0);
        $slot2                = $this->createSlot('SLOT002', '2025-01-16', 4, 14, 30, 16, 30);
        $deliverySlotResponse = $this->createDeliverySlotResponse(slots: [$slot1, $slot2]);
        $searchResponse       = new SearchDeliverySlotResponse($deliverySlotResponse);

        $this->searchMock
            ->expects($this->once())
            ->method('searchDeliverySlot')
            ->willReturn($searchResponse);

        $result = $this->service->searchDeliverySlots(
            $this->accountNumber,
            $this->password,
            '2R',
            '1 rue de la Paix',
            '75001',
            'Paris',
            'FR',
            '2025-01-15',
            '2025-01-20',
        );

        $this->assertCount(2, $result->slots);
        $this->assertSame('SLOT001', $result->slots[0]->deliverySlotCode);
        $this->assertSame('SLOT002', $result->slots[1]->deliverySlotCode);
        $this->assertSame(14, $result->slots[1]->startHour);
        $this->assertSame(30, $result->slots[1]->startMinutes);
    }

    public function testGivenSearchFailsWhenSearchSlotsThenThrowsApiError(): void
    {
        $this->searchMock
            ->expects($this->once())
            ->method('searchDeliverySlot')
            ->willReturn(false);

        $this->expectException(ApiError::class);

        $this->service->searchDeliverySlots(
            $this->accountNumber,
            $this->password,
            '2R',
            '1 rue de la Paix',
            '75001',
            'Paris',
            'FR',
            '2025-01-15',
            '2025-01-20',
        );
    }

    public function testGivenNullResponseWhenSearchSlotsThenThrowsApiError(): void
    {
        $searchResponse = new SearchDeliverySlotResponse(null);

        $this->searchMock
            ->expects($this->once())
            ->method('searchDeliverySlot')
            ->willReturn($searchResponse);

        $this->expectException(ApiError::class);

        $this->service->searchDeliverySlots(
            $this->accountNumber,
            $this->password,
            '2R',
            '1 rue de la Paix',
            '75001',
            'Paris',
            'FR',
            '2025-01-15',
            '2025-01-20',
        );
    }

    public function testGivenErrorCodeWhenSearchSlotsThenThrowsDeliverySlotException(): void
    {
        $deliverySlotResponse = $this->createDeliverySlotResponse(code: 1, message: 'Invalid product type');
        $searchResponse       = new SearchDeliverySlotResponse($deliverySlotResponse);

        $this->searchMock
            ->expects($this->once())
            ->method('searchDeliverySlot')
            ->willReturn($searchResponse);

        $this->expectException(DeliverySlotException::class);
        $this->expectExceptionMessage('Invalid product type');
        $this->expectExceptionCode(1);

        $this->service->searchDeliverySlots(
            $this->accountNumber,
            $this->password,
            '2R',
            '1 rue de la Paix',
            '75001',
            'Paris',
            'FR',
            '2025-01-15',
            '2025-01-20',
        );
    }

    public function testGivenOptionalParamsWhenSearchSlotsThenSetsHeaders(): void
    {
        $deliverySlotResponse = $this->createDeliverySlotResponse();
        $searchResponse       = new SearchDeliverySlotResponse($deliverySlotResponse);

        $this->searchMock
            ->expects($this->once())
            ->method('setSoapHeaderAccountNumber')
            ->with('19869502');

        $this->searchMock
            ->expects($this->once())
            ->method('setSoapHeaderPassword')
            ->with('255562');

        $this->searchMock
            ->expects($this->once())
            ->method('searchDeliverySlot')
            ->willReturn($searchResponse);

        $this->service->searchDeliverySlots(
            $this->accountNumber,
            $this->password,
            '2R',
            '1 rue de la Paix',
            '75001',
            'Paris',
            'FR',
            '2025-01-15',
            '2025-01-20',
            '10 rue du Commerce',
            '69001',
            'Lyon',
            'FR',
            1500,
            'P',
        );
    }

    public function testSlotPropertiesAreMappedCorrectly(): void
    {
        $slot = new Slot(
            'CODE42',
            '2025-02-20',
            4,
            8,
            15,
            10,
            45,
            'N2',
            'O',
            '1',
            7,
            true,
            3,
            2,
        );

        $deliverySlotResponse = $this->createDeliverySlotResponse(slots: [$slot]);
        $searchResponse       = new SearchDeliverySlotResponse($deliverySlotResponse);

        $this->searchMock
            ->expects($this->once())
            ->method('searchDeliverySlot')
            ->willReturn($searchResponse);

        $result = $this->service->searchDeliverySlots(
            $this->accountNumber,
            $this->password,
            '2R',
            '1 rue de la Paix',
            '75001',
            'Paris',
            'FR',
            '2025-01-15',
            '2025-01-20',
        );

        $mapped = $result->slots[0];
        $this->assertSame('CODE42', $mapped->deliverySlotCode);
        $this->assertSame('2025-02-20', $mapped->deliveryDate);
        $this->assertSame(4, $mapped->dayOfWeek);
        $this->assertSame(8, $mapped->startHour);
        $this->assertSame(15, $mapped->startMinutes);
        $this->assertSame(10, $mapped->endHour);
        $this->assertSame(45, $mapped->endMinutes);
        $this->assertSame('N2', $mapped->tariffLevel);
        $this->assertSame('O', $mapped->status);
        $this->assertSame('1', $mapped->codeStatus);
        $this->assertSame(7, $mapped->note);
        $this->assertTrue($mapped->incentiveFlag);
        $this->assertSame(3, $mapped->rawRank);
        $this->assertSame(2, $mapped->rank);
    }

    // ── confirmDeliverySlot ────────────────────────────────────────────────

    public function testGivenSlotCodeWhenConfirmThenReturnsConfirmation(): void
    {
        $serviceResponseV2 = $this->createServiceResponseV2();
        $confirmResponse   = new ConfirmDeliverySlotV2Response($serviceResponseV2);

        $this->confirmMock
            ->expects($this->once())
            ->method('confirmDeliverySlotV2')
            ->willReturn($confirmResponse);

        $result = $this->service->confirmDeliverySlot(
            $this->accountNumber,
            $this->password,
            '2R',
            'SLOT001',
            'MESH001',
            'TXN001',
            '1',
            '1',
            '2025-01-15',
        );

        $this->assertSame(0, $result->code);
        $this->assertSame('OK', $result->message);
        $this->assertSame('2R', $result->productCode);
        $this->assertSame('0', $result->serviceCode);
        $this->assertSame('AS1', $result->asCode);
    }

    public function testGivenConfirmFailsWhenConfirmThenThrowsApiError(): void
    {
        $this->confirmMock
            ->expects($this->once())
            ->method('confirmDeliverySlotV2')
            ->willReturn(false);

        $this->expectException(ApiError::class);

        $this->service->confirmDeliverySlot(
            $this->accountNumber,
            $this->password,
            '2R',
            'SLOT001',
            'MESH001',
            'TXN001',
            '1',
            '1',
            '2025-01-15',
        );
    }

    public function testGivenNullResponseWhenConfirmThenThrowsApiError(): void
    {
        $confirmResponse = new ConfirmDeliverySlotV2Response(null);

        $this->confirmMock
            ->expects($this->once())
            ->method('confirmDeliverySlotV2')
            ->willReturn($confirmResponse);

        $this->expectException(ApiError::class);

        $this->service->confirmDeliverySlot(
            $this->accountNumber,
            $this->password,
            '2R',
            'SLOT001',
            'MESH001',
            'TXN001',
            '1',
            '1',
            '2025-01-15',
        );
    }

    public function testGivenErrorCodeWhenConfirmThenThrowsDeliverySlotException(): void
    {
        $serviceResponseV2 = $this->createServiceResponseV2(code: 99, message: 'Slot no longer available');
        $confirmResponse   = new ConfirmDeliverySlotV2Response($serviceResponseV2);

        $this->confirmMock
            ->expects($this->once())
            ->method('confirmDeliverySlotV2')
            ->willReturn($confirmResponse);

        $this->expectException(DeliverySlotException::class);
        $this->expectExceptionMessage('Slot no longer available');
        $this->expectExceptionCode(99);

        $this->service->confirmDeliverySlot(
            $this->accountNumber,
            $this->password,
            '2R',
            'SLOT001',
            'MESH001',
            'TXN001',
            '1',
            '1',
            '2025-01-15',
        );
    }

    public function testGivenConfirmSetsHeadersCorrectly(): void
    {
        $serviceResponseV2 = $this->createServiceResponseV2();
        $confirmResponse   = new ConfirmDeliverySlotV2Response($serviceResponseV2);

        $this->confirmMock
            ->expects($this->once())
            ->method('setSoapHeaderAccountNumber')
            ->with('19869502');

        $this->confirmMock
            ->expects($this->once())
            ->method('setSoapHeaderPassword')
            ->with('255562');

        $this->confirmMock
            ->expects($this->once())
            ->method('confirmDeliverySlotV2')
            ->willReturn($confirmResponse);

        $this->service->confirmDeliverySlot(
            $this->accountNumber,
            $this->password,
            '2R',
            'SLOT001',
            'MESH001',
            'TXN001',
            '1',
            '1',
            '2025-01-15',
        );
    }

    // ── geocodeAddress ─────────────────────────────────────────────────────

    public function testGivenAddressWhenGeocodeThenReturnsCoordinates(): void
    {
        $geocodageResponse = $this->createGeocodageResponse();
        $getResponse       = new GetAdresseGeocodageResponse($geocodageResponse);

        $this->getMock
            ->expects($this->once())
            ->method('getAdresseGeocodage')
            ->willReturn($getResponse);

        $result = $this->service->geocodeAddress(
            $this->accountNumber,
            $this->password,
            '1 rue de la Paix',
            '75001',
            'Paris',
        );

        $this->assertSame(48.8566, $result->latitude);
        $this->assertSame(2.3522, $result->longitude);
        $this->assertSame(1, $result->qualityLevel);
    }

    public function testGivenGeocodeFailsWhenGeocodeThenThrowsApiError(): void
    {
        $this->getMock
            ->expects($this->once())
            ->method('getAdresseGeocodage')
            ->willReturn(false);

        $this->expectException(ApiError::class);

        $this->service->geocodeAddress(
            $this->accountNumber,
            $this->password,
            '1 rue de la Paix',
            '75001',
            'Paris',
        );
    }

    public function testGivenNullResponseWhenGeocodeThenThrowsApiError(): void
    {
        $getResponse = new GetAdresseGeocodageResponse(null);

        $this->getMock
            ->expects($this->once())
            ->method('getAdresseGeocodage')
            ->willReturn($getResponse);

        $this->expectException(ApiError::class);

        $this->service->geocodeAddress(
            $this->accountNumber,
            $this->password,
            '1 rue de la Paix',
            '75001',
            'Paris',
        );
    }

    public function testGivenErrorCodeWhenGeocodeThenThrowsDeliverySlotException(): void
    {
        $geocodageResponse = $this->createGeocodageResponse(code: 5, message: 'Address not found');
        $getResponse       = new GetAdresseGeocodageResponse($geocodageResponse);

        $this->getMock
            ->expects($this->once())
            ->method('getAdresseGeocodage')
            ->willReturn($getResponse);

        $this->expectException(DeliverySlotException::class);
        $this->expectExceptionMessage('Address not found');
        $this->expectExceptionCode(5);

        $this->service->geocodeAddress(
            $this->accountNumber,
            $this->password,
            '1 rue de la Paix',
            '75001',
            'Paris',
        );
    }

    public function testGivenGeocodeSetsHeadersCorrectly(): void
    {
        $geocodageResponse = $this->createGeocodageResponse();
        $getResponse       = new GetAdresseGeocodageResponse($geocodageResponse);

        $this->getMock
            ->expects($this->once())
            ->method('setSoapHeaderAccountNumber')
            ->with('19869502');

        $this->getMock
            ->expects($this->once())
            ->method('setSoapHeaderPassword')
            ->with('255562');

        $this->getMock
            ->expects($this->once())
            ->method('getAdresseGeocodage')
            ->willReturn($getResponse);

        $this->service->geocodeAddress(
            $this->accountNumber,
            $this->password,
            '1 rue de la Paix',
            '75001',
            'Paris',
            '2ème étage',
        );
    }

    public function testGivenAddress2WhenGeocodeThenPassesIt(): void
    {
        $geocodageResponse = $this->createGeocodageResponse();
        $getResponse       = new GetAdresseGeocodageResponse($geocodageResponse);

        $this->getMock
            ->expects($this->once())
            ->method('getAdresseGeocodage')
            ->willReturn($getResponse);

        $result = $this->service->geocodeAddress(
            $this->accountNumber,
            $this->password,
            '1 rue de la Paix',
            '75001',
            'Paris',
            'Apt 3B',
        );

        $this->assertSame(48.8566, $result->latitude);
    }

    // ── DeliverySlotException ──────────────────────────────────────────────

    public function testThrowIfErrorDoesNothingOnZeroCode(): void
    {
        DeliverySlotException::throwIfError(0, 'OK');
        $this->addToAssertionCount(1);
    }

    public function testThrowIfErrorThrowsOnNonZeroCode(): void
    {
        $this->expectException(DeliverySlotException::class);
        $this->expectExceptionCode(42);
        $this->expectExceptionMessage('Something went wrong');

        DeliverySlotException::throwIfError(42, 'Something went wrong');
    }

    public function testThrowIfErrorUsesDefaultMessageOnNonZeroCode(): void
    {
        $this->expectException(DeliverySlotException::class);
        $this->expectExceptionCode(1);
        $this->expectExceptionMessage('A delivery slot error occurred.');

        DeliverySlotException::throwIfError(1);
    }
}
