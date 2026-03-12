<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup;

use Kwaadpepper\ChronopostApiPhp\ObjectValues\Email;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PersonName;

/**
 * Address of a DPD recipient for European pickup.
 */
readonly class DpdRecipientAddress
{
    public function __construct(
        private ?string $address = null,
        private ?string $addressLine2 = null,
        private ?string $countryCode = null,
        private ?string $postalCode = null,
        private ?string $digicode = null,
        private ?string $floor = null,
        private ?Email $email = null,
        private ?PersonName $lastName = null,
        private ?float $weight = null,
        private ?PersonName $firstName = null,
        private ?string $companyName = null,
        private ?string $recipientReference = null,
        private ?string $phone = null,
        private ?string $city = null,
    ) {
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function getAddressLine2(): ?string
    {
        return $this->addressLine2;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function getDigicode(): ?string
    {
        return $this->digicode;
    }

    public function getFloor(): ?string
    {
        return $this->floor;
    }

    public function getEmail(): ?Email
    {
        return $this->email;
    }

    public function getLastName(): ?PersonName
    {
        return $this->lastName;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function getFirstName(): ?PersonName
    {
        return $this->firstName;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function getRecipientReference(): ?string
    {
        return $this->recipientReference;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }
}
