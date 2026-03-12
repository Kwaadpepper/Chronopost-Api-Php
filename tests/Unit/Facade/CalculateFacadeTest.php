<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\Facade;

use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\DeliveryTime;
use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\ProductCatalog;
use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\ProductList;
use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\QuickCostV3;
use Kwaadpepper\ChronopostApiPhp\Enums\CountryForChronopost;
use Kwaadpepper\ChronopostApiPhp\Enums\ShippingType;
use Kwaadpepper\ChronopostApiPhp\Facade\CalculateFacade;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ProductCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ServiceCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ShippingEstimateRequest;
use Kwaadpepper\ChronopostApiPhp\Services\Calculate\CalculateService;
use Kwaadpepper\ChronopostApiPhp\Services\Cost\QuickCostService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class CalculateFacadeTest extends TestCase
{
    private CalculateService&MockObject $calculateMock;

    private QuickCostService&MockObject $quickCostMock;

    private CalculateFacade $facade;

    protected function setUp(): void
    {
        $this->calculateMock = $this->createMock(CalculateService::class);
        $this->quickCostMock = $this->createMock(QuickCostService::class);
        $this->facade = new CalculateFacade($this->calculateMock, $this->quickCostMock);
    }

    public function testCalculateDeliveryTimeDelegatesToService(): void
    {
        // GIVEN.
        $from        = new PostCode('72000', CountryForChronopost::FRANCE);
        $to          = new PostCode('75001', CountryForChronopost::FRANCE);
        $productCode = new ProductCode('01');
        $serviceCode = $this->createMock(ServiceCode::class);
        $expected    = $this->createMock(DeliveryTime::class);

        $this->calculateMock->method('calculateDeliveryTime')
            ->willReturn($expected);

        // WHEN.
        $result = $this->facade->calculateDeliveryTime(
            $from,
            $to,
            'Paris',
            $productCode,
            ShippingType::MERCHANDISE,
            $serviceCode,
        );

        // THEN.
        $this->assertSame($expected, $result);
    }

    public function testCalculatePossibleProductsDelegatesToService(): void
    {
        // GIVEN.
        $request  = $this->createMock(ShippingEstimateRequest::class);
        $expected = $this->createMock(ProductList::class);

        $this->calculateMock->method('calculateProducts')
            ->with($request)
            ->willReturn($expected);

        // WHEN.
        $result = $this->facade->calculatePossibleProductsForShipping($request);

        // THEN.
        $this->assertSame($expected, $result);
    }

    public function testCalculatePossibleProductsV2DelegatesToService(): void
    {
        // GIVEN.
        $request  = $this->createMock(ShippingEstimateRequest::class);
        $expected = $this->createMock(ProductList::class);

        $this->calculateMock->method('calculateProductsV2')
            ->willReturn($expected);

        // WHEN.
        $result = $this->facade->calculatePossibleProductsForShippingV2('CALLER', $request);

        // THEN.
        $this->assertSame($expected, $result);
    }

    public function testCalculateShippingCostDelegatesToService(): void
    {
        // GIVEN.
        $from        = new PostCode('72000', CountryForChronopost::FRANCE);
        $to          = new PostCode('75001', CountryForChronopost::FRANCE);
        $productCode = new ProductCode('01');
        $expected    = $this->createMock(QuickCostV3::class);

        $this->quickCostMock->method('quickCostV3')
            ->willReturn($expected);

        // WHEN.
        $result = $this->facade->calculateShippingCost(
            $from,
            $to,
            5000,
            $productCode,
            ShippingType::MERCHANDISE,
        );

        // THEN.
        $this->assertSame($expected, $result);
    }

    public function testGetAvailableProductsDelegatesToService(): void
    {
        // GIVEN.
        $request  = $this->createMock(ShippingEstimateRequest::class);
        $expected = $this->createMock(ProductCatalog::class);

        $this->quickCostMock->method('getProducts')
            ->with($request)
            ->willReturn($expected);

        // WHEN.
        $result = $this->facade->getAvailableProducts($request);

        // THEN.
        $this->assertSame($expected, $result);
    }
}
