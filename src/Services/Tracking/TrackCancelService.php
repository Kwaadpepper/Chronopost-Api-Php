<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Services\Tracking;

use ChronopostTracking\ClassMap;
use ChronopostTracking\ServiceType\Cancel;
use ChronopostTracking\StructType\CancelListSkybill;
use ChronopostTracking\StructType\CancelSkybill;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\CancelListResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\CancelResult;
use Kwaadpepper\ChronopostApiPhp\Enums\Locale;
use Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError;
use Kwaadpepper\ChronopostApiPhp\Exceptions\Tracking\TrackingException;
use Kwaadpepper\ChronopostApiPhp\Factory\TrackCancelListResultFactory;
use Kwaadpepper\ChronopostApiPhp\Factory\TrackCancelResultFactory;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingV2Locale;
use WsdlToPhp\PackageBase\SoapClientInterface;

class TrackCancelService
{
    private Cancel $cancelService;

    /**
     * Tracking service soap url
     *
     * @var string
     */
    protected static string $serviceUrl = 'https://ws.chronopost.fr/tracking-cxf/TrackingServiceWS?wsdl';

    /**
     * Constructor
     *
     * @param array                                       $soapOptions   Additional options for the soap client.
     * @param \ChronopostTracking\ServiceType\Cancel|null $cancelService Injected cancel service (for testing).
     *
     * @phpcs:disable Generic.Files.LineLength.TooLong
     */
    public function __construct(
        array $soapOptions = [],
        ?Cancel $cancelService = null,
    ) {
        // phpcs:enable
        if ($cancelService !== null) {
            $this->cancelService = $cancelService;
            return;
        }

        $soapOptions = array_merge(
            $soapOptions,
            [
                SoapClientInterface::WSDL_URL => static::$serviceUrl,
                SoapClientInterface::WSDL_CLASSMAP => ClassMap::get(),
            ],
        );

        $this->cancelService = new Cancel($soapOptions);
    }

    /**
     * Cancel a single skybill.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber         $accountNumber
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Password              $password
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingNumber        $trackingNumber
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingV2Locale|null $locale
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\CancelResult
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the API call fails or returns an invalid response.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Tracking\TrackingException If the API returns an error response.
     *
     * @phpcs:disable Generic.Files.LineLength.TooLong
     */
    public function cancelSkybill(
        AccountNumber $accountNumber,
        Password $password,
        TrackingNumber $trackingNumber,
        ?TrackingV2Locale $locale = null,
    ): CancelResult {
        // phpcs:enable
        $locale     = $locale ?? TrackingV2Locale::create(Locale::FR);
        $parameters = new CancelSkybill(
            accountNumber: $accountNumber->getAccountNumber(),
            password: $password->getPassword(),
            language: (string)$locale,
            skybillNumber: (string)$trackingNumber,
        );

        $result = $this->cancelService->cancelSkybill($parameters);

        if ($result === false) {
            $lastError = $this->cancelService->getLastErrorForMethod(
                methodName: Cancel::class . '::cancelSkybill'
            );
            throw new ApiError('Failed to call cancel skybill service', $lastError);
        }

        $response = $result->getReturn();

        if ($response === null) {
            throw new ApiError('Failed to get result from cancel skybill service, null response');
        }

        if ($response->getErrorCode() !== 0) {
            throw new TrackingException(
                (string)$response->getErrorMessage(),
                (int)$response->getErrorCode(),
            );
        }

        return (new TrackCancelResultFactory())->create($response);
    }

    /**
     * Cancel a list of skybills.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber         $accountNumber
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Password              $password
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingNumber[]      $trackingNumbers
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingV2Locale|null $locale
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\CancelListResult
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the API call fails or returns an invalid response.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Tracking\TrackingException If the API returns an error response.
     *
     * @phpcs:disable Generic.Files.LineLength.TooLong
     */
    public function cancelListSkybill(
        AccountNumber $accountNumber,
        Password $password,
        array $trackingNumbers,
        ?TrackingV2Locale $locale = null,
    ): CancelListResult {
        // phpcs:enable
        $locale            = $locale ?? TrackingV2Locale::create(Locale::FR);
        $skybillNumbersCsv = implode(
            ',',
            array_map(fn (TrackingNumber $tn) => (string)$tn, $trackingNumbers),
        );

        $parameters = new CancelListSkybill(
            accountNumber: $accountNumber->getAccountNumber(),
            password: $password->getPassword(),
            language: (string)$locale,
            skybillNumber: $skybillNumbersCsv,
        );

        $result = $this->cancelService->cancelListSkybill($parameters);

        if ($result === false) {
            $lastError = $this->cancelService->getLastErrorForMethod(
                methodName: Cancel::class . '::cancelListSkybill'
            );
            throw new ApiError('Failed to call cancel list skybill service', $lastError);
        }

        $response = $result->getReturn();

        if ($response === null) {
            throw new ApiError('Failed to get result from cancel list skybill service, null response');
        }

        if ($response->getErrorCode() !== 0) {
            throw new TrackingException(
                (string)$response->getErrorMessage(),
                (int)$response->getErrorCode(),
            );
        }

        return (new TrackCancelListResultFactory())->create($response);
    }
}
