<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup;

/**
 * A single DPD recipient for European pickup (replaces DestinataireDpd StructType).
 */
readonly class DpdRecipient
{
    public function __construct(
        private ?DpdRecipientAddress $address = null,
        private ?DpdClientInfo $clientInfo = null,
        private ?DpdParticularities $particularities = null,
        private ?float $insuredValue = null,
    ) {
    }

    public function getAddress(): ?DpdRecipientAddress
    {
        return $this->address;
    }

    public function getClientInfo(): ?DpdClientInfo
    {
        return $this->clientInfo;
    }

    public function getParticularities(): ?DpdParticularities
    {
        return $this->particularities;
    }

    public function getInsuredValue(): ?float
    {
        return $this->insuredValue;
    }
}
