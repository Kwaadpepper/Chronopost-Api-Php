<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Feature;

use Kwaadpepper\ChronopostApiPhp\ChronopostApi;
use Kwaadpepper\ChronopostApiPhp\Enums\CountryForChronopost;
use Kwaadpepper\ChronopostApiPhp\Exceptions\DeliverySlot\DeliverySlotException;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\GeocodingAddress;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode;
use PHPUnit\Framework\TestCase;

/**
 * Feature tests for DeliverySlotFacade — real SOAP calls via ChronopostApi.
 *
 * @group integration
 *
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class DeliverySlotFacadeTest extends TestCase
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
     * Highway test: geocode a known Paris address.
     * The test account is not subscribed to the DeliverySlot service ("Invalid accesColis password"),
     * so a DeliverySlotException is expected. This confirms the SOAP call + error handling work.
     */
    public function testGeocodeAddressParisThrowsOnTestAccount(): void
    {
        $this->expectException(DeliverySlotException::class);

        $address = new GeocodingAddress(
            '10 Rue de Rivoli',
            new PostCode('75001', CountryForChronopost::FRANCE),
            'Paris',
        );

        $this->api->deliverySlot->geocodeAddress($address);
    }
}
