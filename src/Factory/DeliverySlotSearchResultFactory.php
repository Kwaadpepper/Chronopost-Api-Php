<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Factory;

use ChronopostTimeSlot\StructType\Slot;
use Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot\DeliverySlot;
use Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot\DeliverySlotSearchResult;

class DeliverySlotSearchResultFactory implements Factory
{
    /**
     * Create a new instance of the factory.
     */
    public function __construct()
    {
    }

    /** @param \ChronopostTimeSlot\StructType\DeliverySlotResponse $response */
    public function create($response): DeliverySlotSearchResult
    {
        $slots = $response->getSlotList() ?? [];

        return new DeliverySlotSearchResult(
            $response->getMeshCode(),
            $response->getTransactionID(),
            array_map([$this, 'mapSlot'], $slots),
        );
    }

    private function mapSlot(Slot $slot): DeliverySlot
    {
        return new DeliverySlot(
            $slot->getDeliverySlotCode(),
            $slot->getDeliveryDate(),
            $slot->getDayOfWeek(),
            $slot->getStartHour(),
            $slot->getStartMinutes(),
            $slot->getEndHour(),
            $slot->getEndMinutes(),
            $slot->getTariffLevel(),
            $slot->getStatus(),
            $slot->getCodeStatus(),
            $slot->getNote(),
            $slot->getIncentiveFlag(),
            $slot->getRawRank(),
            $slot->getRank(),
        );
    }
}
