<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues;

use Kwaadpepper\ChronopostApiPhp\Enums\Locale;

readonly class TrackingV2Locale implements \Stringable
{
    /**
     * The allowed locales for the tracking V2 API.
     * These are the locales that can be used in the tracking V2 API.
     * If a locale is not in this list, it will throw an exception.
     *
     * @var \Kwaadpepper\ChronopostApiPhp\Enums\Locale[]
     */
    private const ALLOWED_LOCALES = [
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
        if (!in_array($locale, self::ALLOWED_LOCALES, true)) {
            throw new \InvalidArgumentException(
                "Locale `{$locale->value}` must be one of: " .
                implode(', ', array_map(
                    fn ($locale) => $locale->value,
                    self::ALLOWED_LOCALES,
                )),
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
