<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup;

/**
 * Container for DPD recipients (replaces DestinatairesDpd StructType).
 */
readonly class DpdRecipients
{
    /** @var DpdRecipient[] */
    private array $recipients;

    public function __construct(DpdRecipient ...$recipients)
    {
        $this->recipients = $recipients;
    }

    /**
     * @return DpdRecipient[]
     */
    public function getRecipients(): array
    {
        return $this->recipients;
    }
}
