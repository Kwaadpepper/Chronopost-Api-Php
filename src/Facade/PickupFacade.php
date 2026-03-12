<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Facade;

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
use Kwaadpepper\ChronopostApiPhp\Services\Shipping\PickupService;

class PickupFacade
{
    public function __construct(
        private PickupService $pickupService,
    ) {
    }

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
    ): PickupFeasibility {
        return $this->pickupService->checkFeasibility(
            $shipper,
            $retrievalDateTime,
            $closingDateTime,
        );
    }

    /**
     * Search pickup constraints for a location.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\PickupException
     */
    public function searchConstraints(
        PickupSearchCriteria $criteria,
    ): PickupConstraints {
        return $this->pickupService->searchConstraints($criteria);
    }

    /**
     * Create a national pickup (ESD).
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\PickupException
     *
     * @phpcs:disable Generic.Files.LineLength.TooLong
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
    ): PickupCreationResult {
        // phpcs:enable
        return $this->pickupService->createNationalPickup(
            $header,
            $datePassage,
            $datePassageFermeture,
            $orderGiver,
            $pickupAddress,
            $esdParticularities,
            $referenceEsdClient,
            $contenu,
            $options,
            $locale,
        );
    }

    /**
     * Create a European pickup (ESD).
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\PickupException
     *
     * @phpcs:disable Generic.Files.LineLength.TooLong
     */
    public function createEuropeanPickup(
        PickupHeader $header,
        string $datePassage,
        OrderGiver $orderGiver,
        PickupAddress $pickupAddress,
        ?DpdRecipients $dpdRecipients = null,
        ?string $locale = null,
    ): PickupCreationResult {
        // phpcs:enable
        return $this->pickupService->createEuropeanPickup(
            $header,
            $datePassage,
            $orderGiver,
            $pickupAddress,
            $dpdRecipients,
            $locale,
        );
    }

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
    ): CancelPickupResult {
        return $this->pickupService->cancelPickups($esdNumbers, $locale);
    }
}
