<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Feature;

use Kwaadpepper\ChronopostApiPhp\ChronopostApi;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingNumber;
use PHPUnit\Framework\TestCase;

/**
 * Feature tests for TrackingFacade — real SOAP calls via ChronopostApi.
 *
 * @group integration
 *
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class TrackingFacadeTest extends TestCase
{
    private ChronopostApi $api;

    protected function setUp(): void
    {
        $this->api = new ChronopostApi(
            new AccountNumber('19869502'),
            new Password('255562'),
        );
    }

    /**
     * Highway test: track a known shipment through the facade.
     */
    public function testTrackShipmentByNumber(): void
    {
        $result = $this->api->tracking->trackShipment(
            new TrackingNumber('XY710284045JB'),
        );

        $this->assertIsArray($result);
    }

    public function testTrackBySenderReference(): void
    {
        $result = $this->api->tracking->trackBySenderReference('REF-TEST-001');

        $this->assertNotNull($result);
    }
}
