<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\QuickCost;

class DeliveryTime
{
    public function __construct(
        public \DateTimeImmutable $deliveryDate,
    ) {
    }
}
