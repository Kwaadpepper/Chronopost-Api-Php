<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Enums;

enum Locale: string
{
    case FR = 'fr_FR';
    case EN = 'en_GB';
    case IT = 'it_IT';
    case ES = 'es_ES';
    case DE = 'de_DE';
    case NL = 'nl_NL';
    case PT = 'pt_PT';

    case US = 'en_US';

    /**
     * Get the name of the locale
     *
     * @return string
     */
    public function name(): string
    {
        return match ($this) {
            self::FR => 'Français',
            self::EN => 'English',
            self::IT => 'Italiano',
            self::ES => 'Español',
            self::DE => 'Deutsch',
            self::NL => 'Nederlands',
            self::PT => 'Português',
            self::US => 'English (United States)',
        };
    }
}
