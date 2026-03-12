<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\Shipping;

use Kwaadpepper\ChronopostApiPhp\Dto\Dto;

readonly class PickupConstraints implements Dto
{
    /**
     * @param PickupConstraint[] $constraints
     */
    public function __construct(
        public int $errorCode,
        public ?string $errorMessage,
        public array $constraints,
    ) {
    }
}
