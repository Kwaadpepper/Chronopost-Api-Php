<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Feature;

use Kwaadpepper\ChronopostApiPhp\ChronopostApi;
use Kwaadpepper\ChronopostApiPhp\Enums\CountryForChronopost;
use Kwaadpepper\ChronopostApiPhp\Enums\ShippingType;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ProductCode;
use Kwaadpepper\ChronopostApiPhp\Services\Cost\QuickCostService;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class QuickCostServiceTest extends TestCase
{
    public function testCanInstantiateQuickCostService(): void
    {
        // WHEN.
        new QuickCostService(
            new AccountNumber('19869502'),
            new Password('255562'),
        );

        // THEN.
        $this->expectNotToPerformAssertions();
    }

    public function testCanGetQuickCost(): void
    {
        // GIVEN.
        $accountNumber    = new AccountNumber('19869502');
        $password         = new Password('255562');
        $from             = new PostCode(
            '75001',
            CountryForChronopost::FRANCE,
        );
        $to               = new PostCode(
            '67420',
            CountryForChronopost::FRANCE,
        );
        $weight           = 12.50;
        $productCode      = new ProductCode('01');
        $shippingType     = ShippingType::MERCHANDISE;
        $quickCostService = new QuickCostService($accountNumber, $password);

        // WHEN.
        $result = $quickCostService->quickCostV3(
            $from,
            $to,
            $weight,
            $productCode,
            $shippingType,
        );

        // THEN.
        $this->expectNotToPerformAssertions();
    }

    public function testCanGetQuickCostWithChronopostApiClass(): void
    {
        // GIVEN.
        $accountNumber = new AccountNumber('19869502');
        $password      = new Password('255562');
        $from          = new PostCode(
            '75001',
            CountryForChronopost::FRANCE,
        );
        $to            = new PostCode(
            '67420',
            CountryForChronopost::FRANCE,
        );
        $weight        = 1250;
        $productCode   = new ProductCode('01');
        $shippingType  = ShippingType::MERCHANDISE;
        $chronopostApi = new ChronopostApi($accountNumber, $password);

        // WHEN.
        $chronopostApi->estimateShippingCost(
            $from,
            $to,
            $weight,
            $productCode,
            $shippingType,
        );

        // THEN.
        $this->expectNotToPerformAssertions();
    }
}
