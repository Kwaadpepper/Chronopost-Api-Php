<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Contracts;

use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\CancelPickupResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\PickupConstraints;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\PickupCreationResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\PickupFeasibility;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\DpdRecipients;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\EsdParticularities;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\OrderGiver;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\PickupAddress;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\PickupHeader;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\PickupOptions;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\PickupShipper;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PickupSearchCriteria;

interface Pickup
{
    /**
     * Check if a pickup (ESD) is feasible.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\PickupException
     */
    public function checkFeasibility(
        PickupShipper $shipper,
        string $retrievalDateTime,
        string $closingDateTime,
    ): PickupFeasibility;

    /**
     * Search pickup constraints for a location.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\PickupException
     */
    public function searchConstraints(
        PickupSearchCriteria $criteria,
    ): PickupConstraints;

    /**
     * Create a national pickup (ESD).
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\PickupException
     */
    public function createNationalPickup(
        PickupHeader $header,
        string $datePassage,
        string $datePassageFermeture,
        OrderGiver $orderGiver,
        PickupAddress $pickupAddress,
        ?EsdParticularities $esdParticularities = null,
        ?string $referenceEsdClient = null,
        ?string $contenu = null,
        ?PickupOptions $options = null,
        ?string $locale = null,
    ): PickupCreationResult;

    /**
     * Create a European pickup (ESD).
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\PickupException
     */
    public function createEuropeanPickup(
        PickupHeader $header,
        string $datePassage,
        OrderGiver $orderGiver,
        PickupAddress $pickupAddress,
        ?DpdRecipients $dpdRecipients = null,
        ?string $locale = null,
    ): PickupCreationResult;

    /**
     * Cancel one or more pickups (ESD).
     *
     * @param string[] $esdNumbers
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\PickupException
     */
    public function cancelPickups(
        array $esdNumbers,
        ?string $locale = null,
    ): CancelPickupResult;
}
