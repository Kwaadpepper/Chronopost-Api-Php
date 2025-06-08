<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Enums;

enum SkyBillWithReservation: int
{
    case DEFAULT_NO_RESERVATION      = 0;
    case WITH_RESERVATION            = 1;
    case WITH_RESERVATION_AND_FORMAT = 2;
}
