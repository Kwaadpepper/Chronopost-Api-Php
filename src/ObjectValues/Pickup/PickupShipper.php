<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup;

use Kwaadpepper\ChronopostApiPhp\Enums\Civility;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Email;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PersonName;

/**
 * Shipper information for pickup feasibility check.
 */
readonly class PickupShipper
{
    public function __construct(
        private ?string $address1 = null,
        private ?string $address2 = null,
        private ?string $city = null,
        private ?Civility $civility = null,
        private ?PersonName $contactName = null,
        private ?string $country = null,
        private ?string $countryName = null,
        private ?Email $email = null,
        private ?string $mobilePhone = null,
        private ?string $name = null,
        private ?string $name2 = null,
        private ?string $phone = null,
        private ?int $preAlert = null,
        private ?string $zipCode = null,
    ) {
    }

    public function getAddress1(): ?string
    {
        return $this->address1;
    }

    public function getAddress2(): ?string
    {
        return $this->address2;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getCivility(): ?Civility
    {
        return $this->civility;
    }

    public function getContactName(): ?PersonName
    {
        return $this->contactName;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function getCountryName(): ?string
    {
        return $this->countryName;
    }

    public function getEmail(): ?Email
    {
        return $this->email;
    }

    public function getMobilePhone(): ?string
    {
        return $this->mobilePhone;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getName2(): ?string
    {
        return $this->name2;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getPreAlert(): ?int
    {
        return $this->preAlert;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }
}
