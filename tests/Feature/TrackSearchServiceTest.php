<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Feature;

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
}
