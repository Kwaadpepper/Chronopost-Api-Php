<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Enums;

enum ShippingType: string
{
    case MERCHANDISE = 'MAR';
    case DOCUMENTS   = 'DOC';

    /**
     * Get the one-letter code for the shipping type.
     *
     * @return string The one-letter code.
     */
    public function oneLetterCode(): string
    {
        return match ($this) {
            self::MERCHANDISE => 'M',
            self::DOCUMENTS   => 'D',
        };
    }
}
