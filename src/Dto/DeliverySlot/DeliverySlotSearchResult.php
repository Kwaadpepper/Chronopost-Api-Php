<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot;

use Kwaadpepper\ChronopostApiPhp\Dto\Dto;

/**
 * Result of a delivery slot search containing mesh code, transaction ID, and available slots.
 */
class DeliverySlotSearchResult implements Dto
{
    /**
     * @param \Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot\DeliverySlot[] $slots
     */
    public function __construct(
        public readonly ?string $meshCode,
        public readonly ?string $transactionId,
        public readonly array $slots,
    ) {
    }
}
