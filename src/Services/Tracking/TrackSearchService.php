<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Services\Tracking;

use ChronopostTracking\ClassMap;
use ChronopostTracking\ServiceType\Track;
use ChronopostTracking\StructType\EventInfoComp;
use ChronopostTracking\StructType\TrackSkybillV2;
use Kwaadpepper\ChronopostApiPhp\Enums\Locale;
use Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError;
use Kwaadpepper\ChronopostApiPhp\Exceptions\TrackingException;
use Kwaadpepper\ChronopostApiPhp\Factory\TrackingSkybillV2EventFactory;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingV2Locale;
use WsdlToPhp\PackageBase\SoapClientInterface;

class TrackSearchService
{
    /**
     * Soap tracking service
     *
     * @var \ChronopostTracking\ServiceType\Track
     */
    private Track $trackService;

    /**
     * Tracking service soap url
     *
     * @var string
     */
    protected static string $serviceUrl = 'https://ws.chronopost.fr/tracking-cxf/TrackingServiceWS?wsdl';

    /**
     * Constructor
     *
     * @param array $soapOptions Additional options for the soap client.
     */
    public function __construct(
        array $soapOptions = []
    ) {
        $soapOptions = array_merge(
            $soapOptions,
            [
                SoapClientInterface::WSDL_URL => static::$serviceUrl,
                SoapClientInterface::WSDL_CLASSMAP => ClassMap::get(),
            ],
        );

        $this->trackService = new Track($soapOptions);
    }

    /**
     * Find tracking information using a tracking number.
     *
     * @phpcs:disable Generic.Files.LineLength.TooLong
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingNumber   $trackingNumber The tracking number to search for.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingV2Locale $locale         The language for the response (default is 'fr').
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SkybillV2\EventInfo[] An array of tracking events.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError          If the API call fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\TrackingException If the tracking number is invalid or if there are no events found.
     */
    public function findUsingTrackingNumber(TrackingNumber $trackingNumber, TrackingV2Locale $locale = null): array
    {
        // phpcs:enable
        $locale     = $locale ?? TrackingV2Locale::create(Locale::FR);
        $parameters = new TrackSkybillV2(
            language: (string)$locale,
            skybillNumber: (string)$trackingNumber,
        );

        $result = $this->trackService->trackSkybillV2($parameters);

        if ($result === false) {
            $lastError = $this->trackService->getLastErrorForMethod(methodName: 'trackSkybillV2');
            throw new ApiError('Failed to call from tracking service', $lastError);
        }

        $response = $result->getReturn();

        if ($response === null) {
            throw new ApiError('Failed to get result from tracking service, null response');
        }

        if ($response->getErrorCode() !== 0) {
            $errorMessage = $response->getErrorMessage();
            $errorCode    = $response->getErrorCode();

            throw new TrackingException($errorMessage, $errorCode);
        }

        $events = $response->getListEventInfoComp()?->getEvents() ?? [];

        $factory = new TrackingSkybillV2EventFactory();

        return array_map(
            fn (EventInfoComp $event) => $factory->create($event),
            $events
        );
    }
}
