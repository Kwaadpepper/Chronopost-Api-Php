<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Enums;

/**
 * Product type for delivery slot (PRECISE / Chronofresh) services.
 *
 * @see https://ws.chronopost.fr/rdv-cxf/services/CreneauServiceWS?wsdl
 */
enum SlotProductType: string
{
    /** PRECISE standard delivery slots. */
    case RDV = 'RDV';

    /** Chronofresh cold-positive delivery slots. */
    case FRESH = 'FRESH';

    /** Chronofresh cold-negative delivery slots. */
    case FREEZE = 'FREEZE';

    /** Ambient PRECISE delivery slots. */
    case AMBIENT = 'AMBIENT';
}
