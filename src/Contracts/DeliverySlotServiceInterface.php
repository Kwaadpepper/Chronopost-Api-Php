<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Contracts;

use Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot\DeliverySlotConfirmation;
use Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot\DeliverySlotSearchResult;
use Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot\GeocodingResult;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\GeocodingAddress;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\SlotConfirmRequest;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\SlotSearchCriteria;

interface DeliverySlotServiceInterface
{
    /**
     * Search for available delivery time slots.
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot\DeliverySlotSearchResult
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\DeliverySlot\DeliverySlotException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     */
    public function searchDeliverySlots(
        SlotSearchCriteria $criteria,
    ): DeliverySlotSearchResult;

    /**
     * Confirm a delivery time slot.
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot\DeliverySlotConfirmation
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\DeliverySlot\DeliverySlotException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     */
    public function confirmDeliverySlot(
        SlotConfirmRequest $request,
    ): DeliverySlotConfirmation;

    /**
     * Geocode an address to get coordinates.
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot\GeocodingResult
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\DeliverySlot\DeliverySlotException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     */
    public function geocodeAddress(
        GeocodingAddress $address,
    ): GeocodingResult;
}
