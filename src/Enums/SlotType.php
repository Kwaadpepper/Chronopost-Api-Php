<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Enums;

/**
 * Type of delivery slot time window.
 *
 * @see https://ws.chronopost.fr/rdv-cxf/services/CreneauServiceWS?wsdl
 */
enum SlotType: string
{
    /** Only daytime slots are returned. */
    case DAY = 'J';

    /** Only evening slots are returned. */
    case EVENING = 'S';

    /** All slots are returned. */
    case ALL = '';
}
