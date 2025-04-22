<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit;

use Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingNumber;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class TrackingNumberTest extends TestCase
{
    public function testCanInstantiateValidTrackingNumber(): void
    {
        // GIVEN.
        $trackingNumber = 'AB123456789CD';

        // WHEN.
        new TrackingNumber($trackingNumber);

        // THEN.
        $this->expectNotToPerformAssertions();
    }

    public function testCannotInstantiateInvalidTrackingNumber(): void
    {
        // THEN.
        $this->expectException(\InvalidArgumentException::class);

        // GIVEN.
        $trackingNumber = 'INVALID_TRACKING_NUMBER';

        // WHEN.
        new TrackingNumber($trackingNumber);
    }
}
