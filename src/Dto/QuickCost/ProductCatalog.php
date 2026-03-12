<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\QuickCost;

use Kwaadpepper\ChronopostApiPhp\Dto\Dto;

class ProductCatalog implements Dto
{
    /**
     * @param \Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\Product[] $products
     */
    public function __construct(public readonly array $products)
    {
        array_map(
            fn (Product $item) => $item,
            $products,
        );
    }
}
