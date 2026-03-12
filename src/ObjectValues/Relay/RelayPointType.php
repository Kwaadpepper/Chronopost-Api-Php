<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay;

enum RelayPointType: string
{
    case CHRONOPOST_AGENCY           = 'A';
    case POST_AGENCY                 = 'B';
    case RELAY_POINT_WITH_DEPOSIT    = 'P';
    case RELAY_POINT_WITHOUT_DEPOSIT = 'C';
    case ANY                         = 'T';
}
