<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues;

use Kwaadpepper\ChronopostApiPhp\Enums\SlotProductType;

/**
 * Composite request for delivery slot confirmation (confirmDeliverySlot).
 */
readonly class SlotConfirmRequest
{
    public function __construct(
        private SlotProductType $productType,
        private string $codeSlot,
        private string $meshCode,
        private string $transactionId,
        private string $rank,
        private string $position,
        private \DateTimeInterface $dateSelected,
    ) {
    }

    public function getProductType(): SlotProductType
    {
        return $this->productType;
    }

    public function getCodeSlot(): string
    {
        return $this->codeSlot;
    }

    public function getMeshCode(): string
    {
        return $this->meshCode;
    }

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    public function getRank(): string
    {
        return $this->rank;
    }

    public function getPosition(): string
    {
        return $this->position;
    }

    public function getDateSelected(): \DateTimeInterface
    {
        return $this->dateSelected;
    }
}
