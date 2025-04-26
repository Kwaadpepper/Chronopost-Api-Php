<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues;

use Kwaadpepper\ChronopostApiPhp\Enums\Locale;

class TrackingV2Locale implements \Stringable
{
    /**
     * @var array
     */
    private array $allowedLocales = [
        Locale::FR,
        Locale::EN,
        Locale::ES,
        Locale::IT,
        Locale::DE,
        Locale::NL,
        Locale::PT,
    ];

    /**
     * @param \Kwaadpepper\ChronopostApiPhp\Enums\Locale $locale
     * @throws \InvalidArgumentException Exception if $locale is not a valid Locale.
     */
    private function __construct(
        private Locale $locale,
    ) {
        if (!in_array($locale, $this->allowedLocales, true)) {
            throw new \InvalidArgumentException(
                "Locale `{$locale->value}` must be one of: " .
                implode(', ', array_map(
                    fn($locale) => $locale->value,
                    $this->allowedLocales
                ))
            );
        }
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->locale->value;
    }

    /**
     * @param \Kwaadpepper\ChronopostApiPhp\Enums\Locale $locale
     * @return \Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingV2Locale
     * @throws \InvalidArgumentException Exception if $locale is not a valid Locale.
     */
    public static function create(Locale $locale): self
    {
        return new self($locale);
    }

    /**
     * @param string $locale
     * @return \Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingV2Locale
     * @throws \InvalidArgumentException Exception if $locale is not a valid Locale.
     */
    public static function createFromString(string $locale): self
    {
        return new self(Locale::from($locale));
    }
}
