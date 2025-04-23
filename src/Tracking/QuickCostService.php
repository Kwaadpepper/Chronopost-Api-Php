<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tracking;

use ChronopostQuickCost\ServiceType\Quick;
use ChronopostTracking\ClassMap;
use ChronopostTracking\StructType\EventInfoComp;
use ChronopostTracking\StructType\TrackSkybillV2;
use Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError;
use Kwaadpepper\ChronopostApiPhp\Exceptions\TrackingException;
use Kwaadpepper\ChronopostApiPhp\Factory\TrackingSkybillEventInfoFactory;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ProductCode;
use WsdlToPhp\PackageBase\SoapClientInterface;

class QuickCostService
{
    /**
     * Soap tracking service
     *
     * @var \ChronopostQuickCost\ServiceType\Quick
     */
    private Quick $quickService;

    /**
     * Tracking service soap url
     *
     * @var string
     */
    protected static string $serviceUrl = 'https://ws.chronopost.fr/quickcost-cxf/QuickcostServiceWS?wsdl';

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

        $this->quickService = new Quick($soapOptions);
    }

    /**
     * Calculate shipping costs with different criterias.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingNumber $trackingNumber The tracking number to search for.
     * @param string                                                    $language       The language for the response (default is 'fr').
     *
     * @return array An array of tracking events.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError          If the API call fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\TrackingException If the tracking number is invalid or if there are no events found.
     */
    public function quickCostV3(ProductCode $productCode, string $language = 'fr'): array
    {
        $parameters = new TrackSkybillV2(
            language: $language,
            skybillNumber: (string)$trackingNumber,
        );

        $result = $this->quickService->quickCostV3($parameters);

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

        $factory = new TrackingSkybillEventInfoFactory();

        return array_map(
            fn (EventInfoComp $event) => $factory->create($event),
            $events
        );
    }
}
