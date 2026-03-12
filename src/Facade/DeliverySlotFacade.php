<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Facade;

use Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot\DeliverySlotConfirmation;
use Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot\DeliverySlotSearchResult;
use Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot\GeocodingResult;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\GeocodingAddress;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\SlotConfirmRequest;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\SlotSearchCriteria;
use Kwaadpepper\ChronopostApiPhp\Services\DeliverySlot\DeliverySlotService;

class DeliverySlotFacade
{
    public function __construct(
        private DeliverySlotService $deliverySlotService,
    ) {
    }

    /**
     * Search for available delivery time slots.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\DeliverySlot\DeliverySlotException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     */
    public function searchDeliverySlots(
        SlotSearchCriteria $criteria,
    ): DeliverySlotSearchResult {
        return $this->deliverySlotService->searchDeliverySlots($criteria);
    }

    /**
     * Confirm a delivery time slot.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\DeliverySlot\DeliverySlotException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     */
    public function confirmDeliverySlot(
        SlotConfirmRequest $request,
    ): DeliverySlotConfirmation {
        return $this->deliverySlotService->confirmDeliverySlot($request);
    }

    /**
     * Geocode an address to get coordinates.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\DeliverySlot\DeliverySlotException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     */
    public function geocodeAddress(
        GeocodingAddress $address,
    ): GeocodingResult {
        return $this->deliverySlotService->geocodeAddress($address);
    }
}
