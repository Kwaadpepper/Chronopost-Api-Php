<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Contracts;

use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\EsdTrackResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SearchTrackResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SenderRefTrackResult;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingSearchCriteria;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingV2Locale;

interface TrackingServiceInterface
{
    /**
     * Find tracking information using a tracking number.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingNumber        $trackingNumber
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingV2Locale|null $locale
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SkybillV2\EventInfo[]
     */
    public function findUsingTrackingNumber(TrackingNumber $trackingNumber, ?TrackingV2Locale $locale = null): array;

    /**
     * Search tracking information using various criteria.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingSearchCriteria $criteria
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingV2Locale|null  $locale
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SearchTrackResult
     */
    public function trackSearch(
        TrackingSearchCriteria $criteria,
        ?TrackingV2Locale $locale = null,
    ): SearchTrackResult;

    /**
     * Track parcels using a sender reference.
     *
     * @param string                                                           $senderRef
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingV2Locale|null $locale
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SenderRefTrackResult
     */
    public function trackWithSenderRef(
        string $senderRef,
        ?TrackingV2Locale $locale = null,
    ): SenderRefTrackResult;

    /**
     * Track ESD (Electronic Signature on Delivery) events.
     *
     * @param string                                                           $esdNumber
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingV2Locale|null $locale
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\EsdTrackResult
     */
    public function trackEsd(
        string $esdNumber,
        ?TrackingV2Locale $locale = null,
    ): EsdTrackResult;
}
