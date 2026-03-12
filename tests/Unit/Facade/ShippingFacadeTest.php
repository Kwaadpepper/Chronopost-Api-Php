<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\Facade;

use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\MonoParcelV7;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\MultiParcelV4;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\ReservationResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\RoutingInfo;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\SkybillLabel;
use Kwaadpepper\ChronopostApiPhp\Facade\ShippingFacade;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\CustomerValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\EsdValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\RecipientValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ReferenceValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ShipperValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\SkyBillValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\RoutingQuery;
use Kwaadpepper\ChronopostApiPhp\Services\Shipping\ShippingLabelService;
use Kwaadpepper\ChronopostApiPhp\Services\Shipping\ShippingService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class ShippingFacadeTest extends TestCase
{
    private ShippingService&MockObject $shippingMock;

    private ShippingLabelService&MockObject $labelMock;

    private ShippingFacade $facade;

    protected function setUp(): void
    {
        $this->shippingMock = $this->createMock(ShippingService::class);
        $this->labelMock    = $this->createMock(ShippingLabelService::class);
        $this->facade       = new ShippingFacade($this->shippingMock, $this->labelMock);
    }

    public function testSingleParcelV4DelegatesToService(): void
    {
        // GIVEN.
        $skybill   = $this->createMock(SkyBillValue::class);
        $customer  = $this->createMock(CustomerValue::class);
        $shipper   = $this->createMock(ShipperValue::class);
        $recipient = $this->createMock(RecipientValue::class);
        $reference = $this->createMock(ReferenceValue::class);
        $expected  = $this->createMock(MultiParcelV4::class);

        $this->shippingMock->method('multiParcelV4')
            ->willReturn($expected);

        // WHEN.
        $result = $this->facade->singleParcelV4(
            $skybill,
            $customer,
            $shipper,
            $recipient,
            $reference,
        );

        // THEN.
        $this->assertSame($expected, $result);
    }

    public function testSingleParcelV7DelegatesToService(): void
    {
        // GIVEN.
        $skybill   = $this->createMock(SkyBillValue::class);
        $customer  = $this->createMock(CustomerValue::class);
        $shipper   = $this->createMock(ShipperValue::class);
        $recipient = $this->createMock(RecipientValue::class);
        $reference = $this->createMock(ReferenceValue::class);
        $expected  = $this->createMock(MonoParcelV7::class);

        $this->shippingMock->method('singleParcelV7')
            ->willReturn($expected);

        // WHEN.
        $result = $this->facade->singleParcelV7(
            $skybill,
            $customer,
            $shipper,
            $recipient,
            $reference,
        );

        // THEN.
        $this->assertSame($expected, $result);
    }

    public function testSingleParcelWithReservationDelegatesToService(): void
    {
        // GIVEN.
        $skybill   = $this->createMock(SkyBillValue::class);
        $customer  = $this->createMock(CustomerValue::class);
        $shipper   = $this->createMock(ShipperValue::class);
        $recipient = $this->createMock(RecipientValue::class);
        $reference = $this->createMock(ReferenceValue::class);
        $expected  = $this->createMock(ReservationResult::class);

        $this->shippingMock->method('singleParcelWithReservation')
            ->willReturn($expected);

        // WHEN.
        $result = $this->facade->singleParcelWithReservation(
            $skybill,
            $customer,
            $shipper,
            $recipient,
            $reference,
        );

        // THEN.
        $this->assertSame($expected, $result);
    }

    public function testShippingWithEsdOnlyDelegatesToService(): void
    {
        // GIVEN.
        $skybill   = $this->createMock(SkyBillValue::class);
        $customer  = $this->createMock(CustomerValue::class);
        $shipper   = $this->createMock(ShipperValue::class);
        $recipient = $this->createMock(RecipientValue::class);
        $reference = $this->createMock(ReferenceValue::class);
        $esd       = $this->createMock(EsdValue::class);
        $expected  = $this->createMock(ReservationResult::class);

        $this->shippingMock->method('shippingWithEsdOnly')
            ->willReturn($expected);

        // WHEN.
        $result = $this->facade->shippingWithEsdOnly(
            $skybill,
            $customer,
            $shipper,
            $recipient,
            $reference,
            $esd,
        );

        // THEN.
        $this->assertSame($expected, $result);
    }

    public function testShippingWithReservationAndEsdDelegatesToService(): void
    {
        // GIVEN.
        $skybill   = $this->createMock(SkyBillValue::class);
        $customer  = $this->createMock(CustomerValue::class);
        $shipper   = $this->createMock(ShipperValue::class);
        $recipient = $this->createMock(RecipientValue::class);
        $reference = $this->createMock(ReferenceValue::class);
        $esd       = $this->createMock(EsdValue::class);
        $expected  = $this->createMock(ReservationResult::class);

        $this->shippingMock->method('shippingWithReservationAndEsd')
            ->willReturn($expected);

        // WHEN.
        $result = $this->facade->shippingWithReservationAndEsd(
            $skybill,
            $customer,
            $shipper,
            $recipient,
            $reference,
            $esd,
        );

        // THEN.
        $this->assertSame($expected, $result);
    }

    public function testGetShippingLabelDelegatesToService(): void
    {
        // GIVEN.
        $expected = $this->createMock(SkybillLabel::class);
        $this->labelMock->method('getSkybill')
            ->with('LT123456', 'PDF', null)
            ->willReturn($expected);

        // WHEN.
        $result = $this->facade->getShippingLabel('LT123456');

        // THEN.
        $this->assertSame($expected, $result);
    }

    public function testGetReservedShippingLabelDelegatesToService(): void
    {
        // GIVEN.
        $expected = $this->createMock(SkybillLabel::class);
        $this->labelMock->method('getReservedSkybillWithTypeAndModeByReservation')
            ->with('RSV001', 'PDF')
            ->willReturn($expected);

        // WHEN.
        $result = $this->facade->getReservedShippingLabel('RSV001');

        // THEN.
        $this->assertSame($expected, $result);
    }

    public function testGetRoutingDelegatesToService(): void
    {
        // GIVEN.
        $query    = $this->createMock(RoutingQuery::class);
        $expected = $this->createMock(RoutingInfo::class);
        $this->labelMock->method('getRouting')
            ->with($query)
            ->willReturn($expected);

        // WHEN.
        $result = $this->facade->getRouting($query);

        // THEN.
        $this->assertSame($expected, $result);
    }
}
