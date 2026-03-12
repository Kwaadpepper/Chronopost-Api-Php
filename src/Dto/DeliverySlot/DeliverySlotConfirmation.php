<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot;

use Kwaadpepper\ChronopostApiPhp\Dto\Dto;

/**
 * Result of a delivery slot confirmation.
 */
class DeliverySlotConfirmation implements Dto
{
    public function __construct(
        public readonly ?int $code,
        public readonly ?string $message,
        public readonly ?string $productCode,
        public readonly ?string $serviceCode,
        public readonly ?string $asCode,
    ) {
    }
}
