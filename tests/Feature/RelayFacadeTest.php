<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Feature;

use DateTimeImmutable;
use Kwaadpepper\ChronopostApiPhp\ChronopostApi;
use Kwaadpepper\ChronopostApiPhp\Enums\CountryForChronopost;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AddressSearch;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Coordinates;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ProductCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\WantedShippingDate;
use PHPUnit\Framework\TestCase;

/**
 * Feature tests for RelayFacade — real SOAP calls via ChronopostApi.
 *
 * @group integration
 *
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class RelayFacadeTest extends TestCase
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
     * Highway test: search relay points near Paris for Chrono Relais 13 (PC=86).
     */
    public function testSearchRelayPointRelais13Paris(): void
    {
        $result = $this->api->relay->searchRelayPoint(
            new ProductCode('86'),
            new AddressSearch(
                new PostCode('75001', CountryForChronopost::FRANCE),
                'Paris',
            ),
            new WantedShippingDate(new DateTimeImmutable('tomorrow')),
        );

        $this->assertNotEmpty($result->relayList);
    }

    public function testSearchRelayPointByCoordinatesParis(): void
    {
        $result = $this->api->relay->searchRelayPointByCoordinates(
            new Coordinates(48.8566, 2.3522),
            new ProductCode('86'),
            new WantedShippingDate(new DateTimeImmutable('tomorrow')),
        );

        $this->assertNotEmpty($result->relayList);
    }
}
