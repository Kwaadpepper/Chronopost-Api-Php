<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Contracts;

use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\EsdTrackResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SearchTrackResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SenderRefTrackResult;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingNumber;
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
     * @phpcs:disable Generic.Files.LineLength.TooLong
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber         $accountNumber
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Password              $password
     * @param string|null                                                      $consigneesCountry
     * @param string|null                                                      $consigneesRef
     * @param string|null                                                      $consigneesZipCode
     * @param string|null                                                      $dateDeposit
     * @param string|null                                                      $dateEndDeposit
     * @param string|null                                                      $parcelState
     * @param string|null                                                      $sendersRef
     * @param string|null                                                      $serviceCode
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingV2Locale|null $locale
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SearchTrackResult
     */
    public function trackSearch(
        AccountNumber $accountNumber,
        Password $password,
        ?string $consigneesCountry = null,
        ?string $consigneesRef = null,
        ?string $consigneesZipCode = null,
        ?string $dateDeposit = null,
        ?string $dateEndDeposit = null,
        ?string $parcelState = null,
        ?string $sendersRef = null,
        ?string $serviceCode = null,
        ?TrackingV2Locale $locale = null,
    ): SearchTrackResult;

    /**
     * Track parcels using a sender reference.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber         $accountNumber
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Password              $password
     * @param string                                                           $senderRef
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingV2Locale|null $locale
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SenderRefTrackResult
     */
    public function trackWithSenderRef(
        AccountNumber $accountNumber,
        Password $password,
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
