<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Facade;

use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\DeliveryTime;
use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\ProductCatalog;
use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\ProductList;
use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\QuickCostV3;
use Kwaadpepper\ChronopostApiPhp\Enums\ShippingType;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ProductCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ServiceCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ShippingEstimateRequest;
use Kwaadpepper\ChronopostApiPhp\Services\Calculate\CalculateService;
use Kwaadpepper\ChronopostApiPhp\Services\Cost\QuickCostService;

class CalculateFacade
{
    public function __construct(
        private CalculateService $calculateService,
        private QuickCostService $quickCostService,
    ) {
    }

    /**
     * Calculate the delivery time for a shipment.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Calculate\CalculateException
     */
    public function calculateDeliveryTime(
        PostCode $from,
        PostCode $to,
        string $toCityName,
        ProductCode $productCode,
        ShippingType $shippingType,
        ServiceCode $serviceCode,
    ): DeliveryTime {
        return $this->calculateService->calculateDeliveryTime(
            $from,
            $to,
            $toCityName,
            $productCode,
            $shippingType,
            $serviceCode,
        );
    }

    /**
     * Calculate possible products for a shipment.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Calculate\CalculateException
     */
    public function calculatePossibleProductsForShipping(
        ShippingEstimateRequest $request,
    ): ProductList {
        return $this->calculateService->calculateProducts($request);
    }

    /**
     * Calculate possible products for a shipment (V2, with caller token).
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Calculate\CalculateException
     */
    public function calculatePossibleProductsForShippingV2(
        string $caller,
        ShippingEstimateRequest $request,
        ?string $nationalite = null,
        ?string $isPart = null,
    ): ProductList {
        return $this->calculateService->calculateProductsV2(
            $caller,
            $request,
            $nationalite,
            $isPart,
        );
    }

    /**
     * Estimate the shipping cost for a shipment.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\QuickCost\QuickCostException
     */
    public function calculateShippingCost(
        PostCode $from,
        PostCode $to,
        int $weightInGrams,
        ProductCode $productCode,
        ShippingType $shippingType,
    ): QuickCostV3 {
        return $this->quickCostService->quickCostV3(
            $from,
            $to,
            $weightInGrams / 1000,
            $productCode,
            $shippingType,
        );
    }

    /**
     * Get available products for a route (without pricing).
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\QuickCost\QuickCostException
     */
    public function getAvailableProducts(
        ShippingEstimateRequest $request,
    ): ProductCatalog {
        return $this->quickCostService->getProducts($request);
    }
}
