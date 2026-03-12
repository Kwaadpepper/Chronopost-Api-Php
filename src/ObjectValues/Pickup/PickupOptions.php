<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup;

/**
 * Pickup notification/processing options.
 */
readonly class PickupOptions
{
    public function __construct(
        private ?bool $notifyOnCompletion = null,
        private ?bool $atThirdParty = null,
        private ?bool $sendLtByEmail = null,
        private ?bool $ltPrintedByChronopost = null,
    ) {
    }

    public function getNotifyOnCompletion(): ?bool
    {
        return $this->notifyOnCompletion;
    }

    public function getAtThirdParty(): ?bool
    {
        return $this->atThirdParty;
    }

    public function getSendLtByEmail(): ?bool
    {
        return $this->sendLtByEmail;
    }

    public function getLtPrintedByChronopost(): ?bool
    {
        return $this->ltPrintedByChronopost;
    }
}
