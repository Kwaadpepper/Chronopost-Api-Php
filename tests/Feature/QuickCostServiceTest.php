<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Feature;

use Kwaadpepper\ChronopostApiPhp\Enums\CountryDelivery;
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
        new QuickCostService();

        // THEN.
        $this->expectNotToPerformAssertions();
    }

    public function testCanGetQuickCost(): void
    {
    // GIVEN.
        $accountNumber    = new AccountNumber('19869502');
        $password         = new Password('255562');
        $from             = PostCode::create(
            '75001',
            CountryDelivery::FRANCE,
        );
        $to               = PostCode::create(
            '67420',
            CountryDelivery::FRANCE,
        );
        $weight           = 12.50;
        $productCode      = new ProductCode('01');
        $shippingType     = ShippingType::MERCHANDISE;
        $quickCostService = new QuickCostService();

        // WHEN.
        $result = $quickCostService->quickCostV3(
            $accountNumber,
            $password,
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
