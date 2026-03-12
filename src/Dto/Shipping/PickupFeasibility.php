<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\Shipping;

use Kwaadpepper\ChronopostApiPhp\Dto\Dto;

readonly class PickupFeasibility implements Dto
{
    public function __construct(
        public int $errorCode,
        public ?string $errorMessage,
        public bool $feasible,
    ) {
    }
}
