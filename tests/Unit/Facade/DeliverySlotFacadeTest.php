<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\Facade;

use Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot\DeliverySlotConfirmation;
use Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot\DeliverySlotSearchResult;
use Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot\GeocodingResult;
use Kwaadpepper\ChronopostApiPhp\Facade\DeliverySlotFacade;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\GeocodingAddress;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\SlotConfirmRequest;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\SlotSearchCriteria;
use Kwaadpepper\ChronopostApiPhp\Services\DeliverySlot\DeliverySlotService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class DeliverySlotFacadeTest extends TestCase
{
    private DeliverySlotService&MockObject $slotServiceMock;

    private DeliverySlotFacade $facade;

    protected function setUp(): void
    {
        $this->slotServiceMock = $this->createMock(DeliverySlotService::class);
        $this->facade = new DeliverySlotFacade($this->slotServiceMock);
    }

    public function testSearchDeliverySlotsDelegatesToService(): void
    {
        // GIVEN.
        $criteria = $this->createMock(SlotSearchCriteria::class);
        $expected = $this->createMock(DeliverySlotSearchResult::class);
        $this->slotServiceMock->method('searchDeliverySlots')
            ->with($criteria)
            ->willReturn($expected);

        // WHEN.
        $result = $this->facade->searchDeliverySlots($criteria);

        // THEN.
        $this->assertSame($expected, $result);
    }

    public function testConfirmDeliverySlotDelegatesToService(): void
    {
        // GIVEN.
        $request  = $this->createMock(SlotConfirmRequest::class);
        $expected = $this->createMock(DeliverySlotConfirmation::class);
        $this->slotServiceMock->method('confirmDeliverySlot')
            ->with($request)
            ->willReturn($expected);

        // WHEN.
        $result = $this->facade->confirmDeliverySlot($request);

        // THEN.
        $this->assertSame($expected, $result);
    }

    public function testGeocodeAddressDelegatesToService(): void
    {
        // GIVEN.
        $address  = $this->createMock(GeocodingAddress::class);
        $expected = $this->createMock(GeocodingResult::class);
        $this->slotServiceMock->method('geocodeAddress')
            ->with($address)
            ->willReturn($expected);

        // WHEN.
        $result = $this->facade->geocodeAddress($address);

        // THEN.
        $this->assertSame($expected, $result);
    }
}
