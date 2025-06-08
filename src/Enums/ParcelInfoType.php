<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Enums;

enum ParcelInfoType: int
{
    case INDIVIDUAL = 1;
    case COMPANY    = 2;
}
