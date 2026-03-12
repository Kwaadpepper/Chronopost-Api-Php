<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Facade;

use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\CancelListResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\CancelResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\EsdTrackResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\ProofOfDelivery;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\ProofOfDeliveryByRef;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SearchTrackResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SenderRefTrackResult;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingSearchCriteria;
use Kwaadpepper\ChronopostApiPhp\Services\Tracking\ProofOfDeliveryService;
use Kwaadpepper\ChronopostApiPhp\Services\Tracking\TrackCancelService;
use Kwaadpepper\ChronopostApiPhp\Services\Tracking\TrackSearchService;

class TrackingFacade
{
    public function __construct(
        private TrackSearchService $trackSearchService,
        private TrackCancelService $trackCancelService,
        private ProofOfDeliveryService $proofOfDeliveryService,
    ) {
    }

    /**
     * Track a single shipment using the tracking number.
     *
     * @param  \Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingNumber $trackingNumber The tracking number to search.
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SkybillV2\EventInfo[] The tracking information.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Tracking\TrackingException
     */
    public function trackShipment(TrackingNumber $trackingNumber): array
    {
        return $this->trackSearchService->findUsingTrackingNumber($trackingNumber);
    }

    /**
     * Search tracking with multiple criteria.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Tracking\TrackingException
     */
    public function trackBySearchQuery(
        TrackingSearchCriteria $criteria,
    ): SearchTrackResult {
        return $this->trackSearchService->trackSearch($criteria);
    }

    /**
     * Track parcels using sender reference.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Tracking\TrackingException
     */
    public function trackBySenderReference(string $senderRef): SenderRefTrackResult
    {
        return $this->trackSearchService->trackWithSenderRef($senderRef);
    }

    /**
     * Track using an ESD number.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Tracking\TrackingException
     */
    public function trackEsd(string $esdNumber): EsdTrackResult
    {
        return $this->trackSearchService->trackEsd($esdNumber);
    }

    /**
     * Cancel a single shipment.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Tracking\TrackingException
     */
    public function cancelShipment(TrackingNumber $trackingNumber): CancelResult
    {
        return $this->trackCancelService->cancelSkybill($trackingNumber);
    }

    /**
     * Cancel multiple shipments.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingNumber[] $trackingNumbers
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Tracking\TrackingException
     */
    public function cancelMultipleShipments(array $trackingNumbers): CancelListResult
    {
        return $this->trackCancelService->cancelListSkybill($trackingNumbers);
    }

    /**
     * Search for proof of delivery by tracking number.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Tracking\TrackingException
     */
    public function getProofOfDelivery(
        TrackingNumber $trackingNumber,
        bool $pdf = true,
    ): ProofOfDelivery {
        return $this->proofOfDeliveryService->searchPod($trackingNumber, $pdf);
    }

    /**
     * Search for proof of delivery by sender reference.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Tracking\TrackingException
     */
    public function getProofOfDeliveryByReference(
        string $senderRef,
        bool $pdf = true,
    ): ProofOfDeliveryByRef {
        return $this->proofOfDeliveryService->searchPodWithSenderRef($senderRef, $pdf);
    }
}
