<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues;

use Kwaadpepper\ChronopostApiPhp\Enums\SlotProductType;
use Kwaadpepper\ChronopostApiPhp\Enums\SlotType;

/**
 * Composite criteria for delivery slot search (searchDeliverySlots).
 */
readonly class SlotSearchCriteria
{
    public function __construct(
        private SlotProductType $productType,
        private Address $recipientAddress,
        private DateRange $dateRange,
        private ?Address $shipperAddress = null,
        private ?Weight $weight = null,
        private ?SlotType $slotType = null,
    ) {
    }

    public function getProductType(): SlotProductType
    {
        return $this->productType;
    }

    public function getRecipientAddress(): Address
    {
        return $this->recipientAddress;
    }

    public function getDateRange(): DateRange
    {
        return $this->dateRange;
    }

    public function getShipperAddress(): ?Address
    {
        return $this->shipperAddress;
    }

    public function getWeight(): ?Weight
    {
        return $this->weight;
    }

    public function getSlotType(): ?SlotType
    {
        return $this->slotType;
    }
}
