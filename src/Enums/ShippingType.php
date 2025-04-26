<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Enums;

enum ShippingType: string
{
    case MERCHANDISE = 'M';
    case DOCUMENTS   = 'D';
}
