<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\Shipping;

use Kwaadpepper\ChronopostApiPhp\Dto\Dto;

readonly class PickupCreationResult implements Dto
{
    /**
     * @param PickupInfo[] $pickupInfos
     */
    public function __construct(
        public string $numeroUniqueESD,
        public array $pickupInfos,
    ) {
    }
}
