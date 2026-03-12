<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup;

use Kwaadpepper\ChronopostApiPhp\Enums\Civility;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Email;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PersonName;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\SenderReference;

/**
 * Pickup address for pickup creation requests (replaces AdresseEnlevementV3).
 */
readonly class PickupAddress
{
    public function __construct(
        private ?Civility $civility = null,
        private ?string $countryCode = null,
        private ?string $doorCode = null,
        private ?string $postalCode = null,
        private ?string $hamlet = null,
        private ?PersonName $lastName = null,
        private ?PersonName $contactName = null,
        private ?string $streetNumber = null,
        private ?bool $doorToDoor = null,
        private ?PersonName $firstName = null,
        private ?string $companyName = null,
        private ?string $buildingFloor = null,
        private ?string $serviceDirection = null,
        private ?string $phone = null,
        private ?string $city = null,
        private ?Email $email = null,
        private ?SenderReference $senderReference = null,
    ) {
    }

    public function getCivility(): ?Civility
    {
        return $this->civility;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function getDoorCode(): ?string
    {
        return $this->doorCode;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function getHamlet(): ?string
    {
        return $this->hamlet;
    }

    public function getLastName(): ?PersonName
    {
        return $this->lastName;
    }

    public function getContactName(): ?PersonName
    {
        return $this->contactName;
    }

    public function getStreetNumber(): ?string
    {
        return $this->streetNumber;
    }

    public function getDoorToDoor(): ?bool
    {
        return $this->doorToDoor;
    }

    public function getFirstName(): ?PersonName
    {
        return $this->firstName;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function getBuildingFloor(): ?string
    {
        return $this->buildingFloor;
    }

    public function getServiceDirection(): ?string
    {
        return $this->serviceDirection;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getEmail(): ?Email
    {
        return $this->email;
    }

    public function getSenderReference(): ?SenderReference
    {
        return $this->senderReference;
    }
}
