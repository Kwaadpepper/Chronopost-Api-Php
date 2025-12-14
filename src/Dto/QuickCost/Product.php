<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\QuickCost;

use Money\Money;

class Product {
    public function __construct(
        public Money $amount,
        public Money $amountTTC,
        public Money $amountTVA,
    ) {
    }
}
