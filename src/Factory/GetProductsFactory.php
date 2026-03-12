<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Factory;

use ChronopostQuickCost\StructType\ProductDesc;
use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\Product;
use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\ProductCatalog;

class GetProductsFactory implements Factory
{
    /**
     * @param \ChronopostQuickCost\StructType\ResultGetProducts $result
     */
    public function create($result): ProductCatalog
    {
        $products = array_map(
            $this->toProduct(...),
            $result->getProductList() ?? [],
        );

        return new ProductCatalog($products);
    }

    private function toProduct(ProductDesc $productDesc): Product
    {
        return new Product(
            $productDesc->getProductCode(),
        );
    }
}
