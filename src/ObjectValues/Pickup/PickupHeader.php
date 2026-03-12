<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup;

/**
 * Header information for pickup creation requests.
 */
readonly class PickupHeader
{
    public function __construct(
        private ?int $accountNumber = null,
        private ?string $idEmit = null,
        private ?string $identWebPro = null,
        private ?int $subAccount = null,
    ) {
    }

    public function getAccountNumber(): ?int
    {
        return $this->accountNumber;
    }

    public function getIdEmit(): ?string
    {
        return $this->idEmit;
    }

    public function getIdentWebPro(): ?string
    {
        return $this->identWebPro;
    }

    public function getSubAccount(): ?int
    {
        return $this->subAccount;
    }
}
