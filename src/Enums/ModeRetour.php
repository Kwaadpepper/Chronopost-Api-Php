<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Enums;

/**
 * Mode de retour (label output mode) for shipping methods.
 */
enum ModeRetour: int
{
    /** Label sent to shipper email address. */
    case EMAIL_LABEL = 1;

    /** No email sending. */
    case NO_EMAIL = 2;

    /** Label printable at post office + SMS sent. */
    case SMS_KIOSK = 3;

    /** Label sent to shipper and final recipient (Shop2Shop). */
    case SHOP2SHOP = 4;
}
