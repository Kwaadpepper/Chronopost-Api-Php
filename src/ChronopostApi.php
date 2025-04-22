<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp;

use Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingNumber;
use Kwaadpepper\ChronopostApiPhp\Tracking\TrackSearchService;
use WsdlToPhp\PackageBase\SoapClientInterface;

class ChronopostApi
{
    /**
     * @var TrackSearchService|null
     */
    private TrackSearchService|null $trackSearchService = null;

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

    public function trackSingleShipment(TrackingNumber $trackingNumber): void
    {
        $this->trackSearchService->findUsingTrackingNumber($trackingNumber);
    }
}
