<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp;

use Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingNumber;
use Kwaadpepper\ChronopostApiPhp\Services\Tracking\TrackSearchService;
use WsdlToPhp\PackageBase\SoapClientInterface;

class ChronopostApi
{
    private TrackSearchService $trackSearchService;

    /**
     * Summary of __construct
     *
     * @param string $accountNumber A chronopost account number.
     * @param string $password      A chronopost api password for the accountm received by a commercial from chronopost.
     */
    public function __construct(
        #[\SensitiveParameter] string $accountNumber,
        #[\SensitiveParameter] string $password
    ) {
        $defaultSopapOptions = [
            SoapClientInterface::WSDL_LOGIN => $accountNumber,
            SoapClientInterface::WSDL_PASSWORD => $password,
        ];

        $this->trackSearchService = new TrackSearchService($defaultSopapOptions);
    }

    /**
     * Track a single shipment using the tracking number.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingNumber $trackingNumber The tracking number to search.
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SkybillV2\EventInfo[] The tracking information.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError          If the API call fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\TrackingException If the tracking number is invalid
     *                                                                    or if there are no events found.
     */
    public function trackSingleShipment(TrackingNumber $trackingNumber): array
    {
        return $this->trackSearchService->findUsingTrackingNumber($trackingNumber);
    }
}
