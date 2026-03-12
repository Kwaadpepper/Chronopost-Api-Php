<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Feature;

use Kwaadpepper\ChronopostApiPhp\ChronopostApi;
use Kwaadpepper\ChronopostApiPhp\Enums\CountryForChronopost;
use Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\PickupException;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\PickupShipper;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PickupSearchCriteria;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode;
use PHPUnit\Framework\TestCase;

/**
 * Feature tests for PickupFacade — real SOAP calls via ChronopostApi.
 *
 * @group integration
 *
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class PickupFacadeTest extends TestCase
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
     * Highway test: search pickup constraints for Le Mans (72000).
     * The test account returns a PickupException ("bu is mandatory"),
     * confirming the SOAP call + error handling work.
     */
    public function testSearchConstraintsLeMansThrowsOnTestAccount(): void
    {
        $this->expectException(PickupException::class);

        $criteria = new PickupSearchCriteria(
            new PostCode('72000', CountryForChronopost::FRANCE),
            'Le Mans',
        );

        $this->api->pickup->searchConstraints($criteria);
    }

    public function testCheckFeasibilityLeMans(): void
    {
        $shipper = new PickupShipper(
            address1: '5 Avenue Bollee',
            city: 'Le Mans',
            country: 'FR',
            name: 'Entreprise Test',
            phone: '0243000000',
            zipCode: '72000',
        );

        $tomorrow    = new \DateTimeImmutable('tomorrow 10:00');
        $closingTime = new \DateTimeImmutable('tomorrow 17:00');

        $result = $this->api->pickup->checkFeasibility(
            $shipper,
            $tomorrow->format('Y-m-d\TH:i:s'),
            $closingTime->format('Y-m-d\TH:i:s'),
        );

        $this->assertNotNull($result);
    }
}
