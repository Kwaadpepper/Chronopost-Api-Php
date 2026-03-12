<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues;

/**
 * Composite address for geocoding (geocodeAddress).
 */
readonly class GeocodingAddress
{
    public function __construct(
        private string $address1,
        private PostCode $postCode,
        private string $city,
        private ?string $address2 = null,
    ) {
        $this->validate($address1, $city, $address2);
    }

    public function getAddress1(): string
    {
        return $this->address1;
    }

    public function getPostCode(): PostCode
    {
        return $this->postCode;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getAddress2(): ?string
    {
        return $this->address2;
    }

    private function validate(string $address1, string $city, ?string $address2): void
    {
        if ($address1 === '') {
            throw new \InvalidArgumentException('Geocoding address line 1 must not be empty.');
        }
        if ($city === '') {
            throw new \InvalidArgumentException('Geocoding city must not be empty.');
        }
        if ($address2 !== null && $address2 === '') {
            throw new \InvalidArgumentException('Geocoding address line 2 must not be an empty string. Use null instead.');
        }
    }
}
