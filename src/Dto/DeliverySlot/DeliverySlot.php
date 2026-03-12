<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot;

use Kwaadpepper\ChronopostApiPhp\Dto\Dto;

/**
 * Represents a single delivery time slot.
 */
class DeliverySlot implements Dto
{
    public function __construct(
        public readonly ?string $deliverySlotCode,
        public readonly ?string $deliveryDate,
        public readonly ?int $dayOfWeek,
        public readonly ?int $startHour,
        public readonly ?int $startMinutes,
        public readonly ?int $endHour,
        public readonly ?int $endMinutes,
        public readonly ?string $tariffLevel,
        public readonly ?string $status,
        public readonly ?string $codeStatus,
        public readonly ?int $note,
        public readonly ?bool $incentiveFlag,
        public readonly ?int $rawRank,
        public readonly ?int $rank,
    ) {
    }
}
