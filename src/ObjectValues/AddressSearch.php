<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues;

class AddressSearch
{
    public function __construct(
        public readonly PostCode $postalCode,
        public readonly string $city,
        public readonly ?string $address = null,
    ) {
        if (preg_match('/^[a-zA-Z0-9 ]{1,50}$/', $city) !== 1) {
            throw new \InvalidArgumentException(
                "City must be 1-50 ASCII alphanumeric characters (got: \"{$city}\"). Transliterate before calling."
            );
        }

        if ($address !== null && preg_match('/^[a-zA-Z0-9 ]{1,200}$/', $address) !== 1) {
            throw new \InvalidArgumentException(
                "Address must be 1-200 ASCII alphanumeric characters (got: \"{$address}\"). Transliterate before calling."
            );
        }
    }
}
