<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Feature;

use Kwaadpepper\ChronopostApiPhp\ChronopostApi;
use Kwaadpepper\ChronopostApiPhp\Enums\CountryForChronopost;
use Kwaadpepper\ChronopostApiPhp\Enums\DeliveryServiceCode;
use Kwaadpepper\ChronopostApiPhp\Enums\ShippingType;
use Kwaadpepper\ChronopostApiPhp\Exceptions\Calculate\CalculateException;
use Kwaadpepper\ChronopostApiPhp\Exceptions\QuickCost\QuickCostException;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ProductCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ServiceCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ShippingEstimateRequest;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Weight;
use PHPUnit\Framework\TestCase;

/**
 * Feature tests for CalculateFacade — real SOAP calls via ChronopostApi.
 *
 * @group integration
 *
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class CalculateFacadeTest extends TestCase
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
     * Highway test: calculate shipping cost for Chrono 13 (PC=01) on a domestic route.
     * Test account returns TTC=0 — we verify the call succeeds and returns a valid DTO.
     */
    public function testCalculateShippingCostChrono13Domestic(): void
    {
        $result = $this->api->calculate->calculateShippingCost(
            new PostCode('72000', CountryForChronopost::FRANCE),
            new PostCode('75001', CountryForChronopost::FRANCE),
            5000,
            new ProductCode('01'),
            ShippingType::MERCHANDISE,
        );

        $this->assertEquals('0', $result->amountTtc->getAmount());
        $this->assertNotEmpty($result->getServices());
    }

    public function testCalculateShippingCostChrono10Domestic(): void
    {
        $result = $this->api->calculate->calculateShippingCost(
            new PostCode('72000', CountryForChronopost::FRANCE),
            new PostCode('69001', CountryForChronopost::FRANCE),
            5000,
            new ProductCode('02'),
            ShippingType::MERCHANDISE,
        );

        $this->assertEquals('0', $result->amountTtc->getAmount());
    }

    public function testCalculateShippingCostRelais13Domestic(): void
    {
        $result = $this->api->calculate->calculateShippingCost(
            new PostCode('72000', CountryForChronopost::FRANCE),
            new PostCode('75001', CountryForChronopost::FRANCE),
            5000,
            new ProductCode('86'),
            ShippingType::MERCHANDISE,
        );

        $this->assertEquals('0', $result->amountTtc->getAmount());
    }

    /**
     * PC=58 (13 BAL) is not subscribed on the test account — expecting QuickCostException.
     */
    public function testCalculateShippingCostBalThrowsException(): void
    {
        $this->expectException(QuickCostException::class);

        $this->api->calculate->calculateShippingCost(
            new PostCode('72000', CountryForChronopost::FRANCE),
            new PostCode('75001', CountryForChronopost::FRANCE),
            5000,
            new ProductCode('58'),
            ShippingType::MERCHANDISE,
        );
    }

    /**
     * The test account does not have calculateDeliveryTime product mapping,
     * so a CalculateException is expected. This confirms the SOAP call + error handling work.
     */
    public function testCalculateDeliveryTimeDomesticThrowsOnTestAccount(): void
    {
        $this->expectException(CalculateException::class);

        $this->api->calculate->calculateDeliveryTime(
            new PostCode('72000', CountryForChronopost::FRANCE),
            new PostCode('75001', CountryForChronopost::FRANCE),
            'Paris',
            new ProductCode('01'),
            ShippingType::MERCHANDISE,
            ServiceCode::fromEnum(DeliveryServiceCode::DELIVERY_ON_MONDAY),
        );
    }

    public function testGetAvailableProductsDomestic(): void
    {
        $request = new ShippingEstimateRequest(
            new PostCode('72000', CountryForChronopost::FRANCE),
            new PostCode('75001', CountryForChronopost::FRANCE),
            'Paris',
            ShippingType::MERCHANDISE,
            new Weight(5.0),
        );

        $result = $this->api->calculate->getAvailableProducts($request);

        $this->assertNotNull($result);
    }

    /**
     * PC=44 (Classic International) is not subscribed for FR destinations — expecting QuickCostException.
     */
    public function testCalculateShippingCostClassicInternationalThrowsForFrDestination(): void
    {
        $this->expectException(QuickCostException::class);

        $this->api->calculate->calculateShippingCost(
            new PostCode('72000', CountryForChronopost::FRANCE),
            new PostCode('1000', CountryForChronopost::BELGIQUE),
            5000,
            new ProductCode('44'),
            ShippingType::MERCHANDISE,
        );
    }

    /**
     * PC=17 (Express International) is not subscribed — expecting QuickCostException.
     */
    public function testCalculateShippingCostExpressInternationalThrows(): void
    {
        $this->expectException(QuickCostException::class);

        $this->api->calculate->calculateShippingCost(
            new PostCode('72000', CountryForChronopost::FRANCE),
            new PostCode('10115', CountryForChronopost::ALLEMAGNE),
            5000,
            new ProductCode('17'),
            ShippingType::MERCHANDISE,
        );
    }

    /**
     * PC=49 (Chrono Relais Europe) is not subscribed — expecting QuickCostException.
     */
    public function testCalculateShippingCostRelaisEuropeThrows(): void
    {
        $this->expectException(QuickCostException::class);

        $this->api->calculate->calculateShippingCost(
            new PostCode('72000', CountryForChronopost::FRANCE),
            new PostCode('28001', CountryForChronopost::ESPAGNE),
            5000,
            new ProductCode('49'),
            ShippingType::MERCHANDISE,
        );
    }

    /**
     * Second test account (Petits pros) — verifies PC=86 and PC=01 also work on account 19999700.
     */
    public function testCalculateShippingCostWithSecondTestAccount(): void
    {
        $api = new ChronopostApi(
            new AccountNumber('19999700'),
            new Password('058888'),
        );

        $result = $api->calculate->calculateShippingCost(
            new PostCode('72000', CountryForChronopost::FRANCE),
            new PostCode('75001', CountryForChronopost::FRANCE),
            5000,
            new ProductCode('01'),
            ShippingType::MERCHANDISE,
        );

        $this->assertEquals('0', $result->amountTtc->getAmount());
    }

    /**
     * Second test account (Petits pros) — PC=86 is NOT subscribed on the old account.
     * Verifying it throws QuickCostException (No match found) as documented.
     */
    public function testCalculateShippingCostRelais13ThrowsOnOldTestAccount(): void
    {
        $this->expectException(QuickCostException::class);

        $api = new ChronopostApi(
            new AccountNumber('19999700'),
            new Password('058888'),
        );

        $api->calculate->calculateShippingCost(
            new PostCode('72000', CountryForChronopost::FRANCE),
            new PostCode('75001', CountryForChronopost::FRANCE),
            5000,
            new ProductCode('86'),
            ShippingType::MERCHANDISE,
        );
    }
}
