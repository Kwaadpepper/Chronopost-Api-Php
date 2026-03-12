<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Contracts;

use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\ProductCatalog;
use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\QuickCostV3;
use Kwaadpepper\ChronopostApiPhp\Enums\ShippingType;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ProductCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ShippingEstimateRequest;

interface QuickCostServiceInterface
{
    public function quickCostV3(
        PostCode $from,
        PostCode $to,
        float $weight,
        ProductCode $productCode,
        ShippingType $shippingType,
    ): QuickCostV3;

    public function getProducts(
        ShippingEstimateRequest $request,
    ): ProductCatalog;
}
