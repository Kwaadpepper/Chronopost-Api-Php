<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup;

use Kwaadpepper\ChronopostApiPhp\Enums\Civility;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Email;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PersonName;

/**
 * Order giver (donneur d'ordre) for pickup creation.
 */
readonly class OrderGiver
{
    public function __construct(
        private ?string $address = null,
        private ?string $building = null,
        private ?Civility $civility = null,
        private ?string $nafCode = null,
        private ?string $countryCode = null,
        private ?string $postalCode = null,
        private ?Email $email = null,
        private ?string $fax = null,
        private ?string $hamlet = null,
        private ?PersonName $lastName = null,
        private ?PersonName $firstName = null,
        private ?string $companyName = null,
        private ?string $service = null,
        private ?string $phone = null,
        private ?string $otherPhone = null,
        private ?string $city = null,
    ) {
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function getBuilding(): ?string
    {
        return $this->building;
    }

    public function getCivility(): ?Civility
    {
        return $this->civility;
    }

    public function getNafCode(): ?string
    {
        return $this->nafCode;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function getEmail(): ?Email
    {
        return $this->email;
    }

    public function getFax(): ?string
    {
        return $this->fax;
    }

    public function getHamlet(): ?string
    {
        return $this->hamlet;
    }

    public function getLastName(): ?PersonName
    {
        return $this->lastName;
    }

    public function getFirstName(): ?PersonName
    {
        return $this->firstName;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function getService(): ?string
    {
        return $this->service;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getOtherPhone(): ?string
    {
        return $this->otherPhone;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }
}
