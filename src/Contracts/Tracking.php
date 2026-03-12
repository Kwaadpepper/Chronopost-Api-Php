<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Contracts;

use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\CancelListResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\CancelResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\EsdTrackResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\ProofOfDelivery;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\ProofOfDeliveryByRef;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SearchTrackResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SenderRefTrackResult;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingSearchCriteria;

interface Tracking
{
    /**
     * Track a single shipment using the tracking number.
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SkybillV2\EventInfo[]
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Tracking\TrackingException
     */
    public function trackShipment(TrackingNumber $trackingNumber): array;

    /**
     * Search tracking with multiple criteria.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Tracking\TrackingException
     */
    public function trackBySearchQuery(
        TrackingSearchCriteria $criteria,
    ): SearchTrackResult;

    /**
     * Track parcels using sender reference.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Tracking\TrackingException
     */
    public function trackBySenderReference(string $senderRef): SenderRefTrackResult;

    /**
     * Track using an ESD number.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Tracking\TrackingException
     */
    public function trackEsd(string $esdNumber): EsdTrackResult;

    /**
     * Cancel a single shipment.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Tracking\TrackingException
     */
    public function cancelShipment(TrackingNumber $trackingNumber): CancelResult;

    /**
     * Cancel multiple shipments.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingNumber[] $trackingNumbers
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Tracking\TrackingException
     */
    public function cancelMultipleShipments(array $trackingNumbers): CancelListResult;

    /**
     * Search for proof of delivery by tracking number.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Tracking\TrackingException
     */
    public function getProofOfDelivery(
        TrackingNumber $trackingNumber,
        bool $pdf = true,
    ): ProofOfDelivery;

    /**
     * Search for proof of delivery by sender reference.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Tracking\TrackingException
     */
    public function getProofOfDeliveryByReference(
        string $senderRef,
        bool $pdf = true,
    ): ProofOfDeliveryByRef;
}
