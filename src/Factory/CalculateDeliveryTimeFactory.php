<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Factory;

use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\DeliveryTime;

class CalculateDeliveryTimeFactory implements Factory
{
    /**
     * Create a QuickCostV3 DTO from Chronopost ResultCalculateDeliveryTime.
     *
     * @param  \ChronopostQuickCost\StructType\ResultCalculateDeliveryTime  $result
     */
    public function create($result): DeliveryTime
    {
        return new DeliveryTime(new \DateTimeImmutable($result->getDate()));
    }
}
