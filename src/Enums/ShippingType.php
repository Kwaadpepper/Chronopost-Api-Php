<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Enums;

enum ShippingType: string
{
    case MERCHANDISE = 'MAR';
    case DOCUMENTS   = 'DOC';
}
