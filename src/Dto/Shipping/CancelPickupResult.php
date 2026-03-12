<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\Shipping;

use Kwaadpepper\ChronopostApiPhp\Dto\Dto;

readonly class CancelPickupResult implements Dto
{
    /**
     * @param array<string, string|null> $statuses
     */
    public function __construct(
        public int $errorCode,
        public ?string $errorMessage,
        public array $statuses,
    ) {
    }
}
