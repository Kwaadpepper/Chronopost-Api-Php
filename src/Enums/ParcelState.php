<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Enums;

/**
 * Parcel state filter for tracking search queries.
 */
enum ParcelState: string
{
    /** Any state. */
    case ANY = 'ANY';

    /** Parcels not yet delivered. */
    case NON_DISTRIBUES = 'NONDISTRIBUES';

    /** Parcels delivered. */
    case DISTRIBUES = 'DISTRIBUES';

    /** International shipments. */
    case INTERNATIONAL = 'INTERNATIONAL';

    /** Parcels pending. */
    case INSTANCE = 'INSTANCE';

    /** Parcels with incident. */
    case INCIDENT = 'INCIDENT';
}
