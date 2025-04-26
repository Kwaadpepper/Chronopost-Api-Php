<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Feature;

use Kwaadpepper\ChronopostApiPhp\ChronopostApi;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingNumber;
use Kwaadpepper\ChronopostApiPhp\Services\Tracking\TrackSearchService;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class TrackSearchServiceTest extends TestCase
{
    public function testCanInstantiateTrackSearchService(): void
    {
        // WHEN.
        new TrackSearchService();

        // THEN.
        $this->expectNotToPerformAssertions();
    }

    public function testCanFindUsingTrackingNumber(): void
    {
        // GIVEN.
        $trackingNumber     = new TrackingNumber('XY710284045JB');
        $trackSearchService = new TrackSearchService();
        // WHEN.
        $result = $trackSearchService->findUsingTrackingNumber($trackingNumber);

        // THEN.
        $this->expectNotToPerformAssertions();
    }

    public function testCanFindTrackingNumberWithChronopostApiClass(): void
    {
        // GIVEN.
        $accountNumber  = new AccountNumber('19869502');
        $password       = new Password('255562');
        $chronopostApi  = new ChronopostApi($accountNumber, $password);
        $trackingNumber = new TrackingNumber('XY710284045JB');

        // WHEN.
        $chronopostApi->trackSingleShipment($trackingNumber);

        // THEN.
        $this->expectNotToPerformAssertions();
    }
}
