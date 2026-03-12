<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\Facade;

use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\PickupConstraints;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\PickupCreationResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\PickupFeasibility;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\CancelPickupResult;
use Kwaadpepper\ChronopostApiPhp\Facade\PickupFacade;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\DpdRecipients;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\EsdParticularities;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\OrderGiver;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\PickupAddress;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\PickupHeader;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\PickupOptions;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\PickupShipper;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PickupSearchCriteria;
use Kwaadpepper\ChronopostApiPhp\Services\Shipping\PickupService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class PickupFacadeTest extends TestCase
{
    private PickupService&MockObject $pickupServiceMock;

    private PickupFacade $facade;

    protected function setUp(): void
    {
        $this->pickupServiceMock = $this->createMock(PickupService::class);
        $this->facade = new PickupFacade($this->pickupServiceMock);
    }

    public function testCheckFeasibilityDelegatesToService(): void
    {
        // GIVEN.
        $shipper  = new PickupShipper();
        $expected = $this->createMock(PickupFeasibility::class);
        $this->pickupServiceMock->method('checkFeasibility')
            ->with($shipper, '2026-03-15T10:00:00', '2026-03-15T17:00:00')
            ->willReturn($expected);

        // WHEN.
        $result = $this->facade->checkFeasibility($shipper, '2026-03-15T10:00:00', '2026-03-15T17:00:00');

        // THEN.
        $this->assertSame($expected, $result);
    }

    public function testSearchConstraintsDelegatesToService(): void
    {
        // GIVEN.
        $criteria = $this->createMock(PickupSearchCriteria::class);
        $expected = $this->createMock(PickupConstraints::class);
        $this->pickupServiceMock->method('searchConstraints')
            ->with($criteria)
            ->willReturn($expected);

        // WHEN.
        $result = $this->facade->searchConstraints($criteria);

        // THEN.
        $this->assertSame($expected, $result);
    }

    public function testCreateNationalPickupDelegatesToService(): void
    {
        // GIVEN.
        $header  = new PickupHeader();
        $og      = new OrderGiver();
        $addr    = new PickupAddress();
        $esd     = new EsdParticularities();
        $opts    = new PickupOptions();
        $expected = $this->createMock(PickupCreationResult::class);

        $this->pickupServiceMock->method('createNationalPickup')
            ->willReturn($expected);

        // WHEN.
        $result = $this->facade->createNationalPickup(
            header: $header,
            datePassage: '2026-03-15T10:00:00',
            datePassageFermeture: '2026-03-15T17:00:00',
            orderGiver: $og,
            pickupAddress: $addr,
            esdParticularities: $esd,
            referenceEsdClient: 'ESD-REF',
            contenu: 'Documents',
            options: $opts,
            locale: 'fr_FR',
        );

        // THEN.
        $this->assertSame($expected, $result);
    }

    public function testCreateEuropeanPickupDelegatesToService(): void
    {
        // GIVEN.
        $header     = new PickupHeader();
        $og         = new OrderGiver();
        $addr       = new PickupAddress();
        $recipients = new DpdRecipients();
        $expected   = $this->createMock(PickupCreationResult::class);

        $this->pickupServiceMock->method('createEuropeanPickup')
            ->willReturn($expected);

        // WHEN.
        $result = $this->facade->createEuropeanPickup(
            $header,
            '2026-03-15T10:00:00',
            $og,
            $addr,
            $recipients,
            'fr_FR',
        );

        // THEN.
        $this->assertSame($expected, $result);
    }

    public function testCancelPickupsDelegatesToService(): void
    {
        // GIVEN.
        $expected = $this->createMock(CancelPickupResult::class);
        $this->pickupServiceMock->method('cancelPickups')
            ->with(['ESD001', 'ESD002'], 'fr_FR')
            ->willReturn($expected);

        // WHEN.
        $result = $this->facade->cancelPickups(['ESD001', 'ESD002'], 'fr_FR');

        // THEN.
        $this->assertSame($expected, $result);
    }
}
