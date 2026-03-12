<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup;

/**
 * Client info for a DPD recipient.
 */
readonly class DpdClientInfo
{
    public function __construct(
        private ?string $content = null,
        private ?string $currency = null,
        private ?float $amount = null,
        private ?string $clientEsdRef = null,
        private ?string $service = null,
    ) {
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function getClientEsdRef(): ?string
    {
        return $this->clientEsdRef;
    }

    public function getService(): ?string
    {
        return $this->service;
    }
}
