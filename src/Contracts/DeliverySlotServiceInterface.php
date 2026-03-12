<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Contracts;

use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;

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
        AccountNumber $accountNumber,
        Password $password,
        string $productType,
        string $recipientAddr1,
        string $recipientZip,
        string $recipientCity,
        string $recipientCountry,
        string $dateBegin,
        string $dateEnd,
        ?string $shipperAddr1 = null,
        ?string $shipperZip = null,
        ?string $shipperCity = null,
        ?string $shipperCountry = null,
        ?int $weight = null,
        ?string $slotType = null,
    ): \Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot\DeliverySlotSearchResult;

    /**
     * Confirm a delivery time slot.
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot\DeliverySlotConfirmation
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\DeliverySlot\DeliverySlotException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     */
    public function confirmDeliverySlot(
        AccountNumber $accountNumber,
        Password $password,
        string $productType,
        string $codeSlot,
        string $meshCode,
        string $transactionId,
        string $rank,
        string $position,
        string $dateSelected,
    ): \Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot\DeliverySlotConfirmation;

    /**
     * Geocode an address to get coordinates.
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot\GeocodingResult
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\DeliverySlot\DeliverySlotException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     */
    public function geocodeAddress(
        AccountNumber $accountNumber,
        Password $password,
        string $address1,
        string $zipCode,
        string $city,
        ?string $address2 = null,
    ): \Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot\GeocodingResult;
}
