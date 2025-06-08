<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Enums;

enum DeliveryServiceCode: string
{
    case NO_DELIVERY_ON_SATURDAY = '0';
    case DELIVERY_ON_MONDAY      = '1';
    case DELIVERY_ON_SATURDAY    = '6';

    /**
     * Get the displayable name of the delivery service.
     *
     * @return string
     */
    public function getDisplayableName(): string
    {
        return match ($this) {
            self::NO_DELIVERY_ON_SATURDAY => 'No delivery on Saturday',
            self::DELIVERY_ON_MONDAY      => 'Delivery on Monday',
            self::DELIVERY_ON_SATURDAY    => 'Delivery on Saturday',
        };
    }
}
