<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Enums;

enum SkyBillOutputMode: int
{
    case TO_SHIPPER_MAIL = 0;

    case NO_MAIL_SENDING = 1;

    case POST_OFFICE_AND_SMS_PRINTABLE = 2;

    case SHOP2SHOP_EMAIL = 3;
}
