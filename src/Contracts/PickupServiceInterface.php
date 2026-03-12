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

interface PickupServiceInterface
{
    public function checkFeasibility(
        PickupShipper $shipper,
        string $retrievalDateTime,
        string $closingDateTime,
    ): PickupFeasibility;

    public function searchConstraints(
        PickupSearchCriteria $criteria,
    ): PickupConstraints;

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

    public function createEuropeanPickup(
        PickupHeader $header,
        string $datePassage,
        OrderGiver $orderGiver,
        PickupAddress $pickupAddress,
        ?DpdRecipients $dpdRecipients = null,
        ?string $locale = null,
    ): PickupCreationResult;

    /**
     * @param string[] $esdNumbers
     */
    public function cancelPickups(
        array $esdNumbers,
        ?string $locale = null,
    ): CancelPickupResult;
}
