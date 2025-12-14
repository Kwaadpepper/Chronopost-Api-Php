<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\QuickCost;

use Kwaadpepper\ChronopostApiPhp\Dto\Dto;

class ProductList implements Dto {
    /**
     * Create a new ProductList DTO.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\Product[] $products
     */
    public function __construct(readonly array $products)
    {
        array_map(
            fn (Product $item) => $item,
            $products
        );
    }
}
