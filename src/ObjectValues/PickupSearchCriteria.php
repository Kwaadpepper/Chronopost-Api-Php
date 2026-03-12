<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues;

/**
 * Composite criteria for pickup constraint search (searchConstraints).
 */
readonly class PickupSearchCriteria
{
    public function __construct(
        private PostCode $postCode,
        private string $city,
    ) {
        if ($city === '') {
            throw new \InvalidArgumentException('City must not be empty.');
        }
    }

    public function getPostCode(): PostCode
    {
        return $this->postCode;
    }

    public function getCity(): string
    {
        return $this->city;
    }
}
