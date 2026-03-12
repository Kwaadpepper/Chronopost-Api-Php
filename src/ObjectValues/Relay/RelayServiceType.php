<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay;

enum RelayServiceType: string
{
    case CHRONOPOST_RELAY = 'L';
    case DEPOT            = 'D';
    case INSTANCE         = 'I';
    case ANY              = 'T';
}
