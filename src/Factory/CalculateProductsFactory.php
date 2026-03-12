<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Factory;

use ChronopostQuickCost\StructType\Product as ChronopostProduct;
use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\Product;
use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\ProductList;

class CalculateProductsFactory implements Factory
{
    /**
     * Create a QuickCostV3 DTO from Chronopost ResultCalculateProducts.
     *
     * @param  \ChronopostQuickCost\StructType\ResultCalculateProducts $result
     */
    public function create($result): ProductList
    {
        $products = array_map(
            $this->toProduct(...),
            $result->getProductList() ?? [],
        );

        return new ProductList($products);
    }

    /**
     * Convert a Chronopost Product to a DTO Product.
     */
    private function toProduct(ChronopostProduct $product): Product
    {
        return new Product(
            $product->getProductCode(),
        );
    }
}
