<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Services\Tracking;

use ChronopostTracking\ClassMap;
use ChronopostTracking\ServiceType\Track;
use ChronopostTracking\StructType\EventInfoComp;
use ChronopostTracking\StructType\TrackESD;
use ChronopostTracking\StructType\TrackSearch;
use ChronopostTracking\StructType\TrackSkybillV2;
use ChronopostTracking\StructType\TrackWithSenderRef;
use Kwaadpepper\ChronopostApiPhp\Contracts\TrackingServiceInterface;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\EsdTrackResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SearchTrackResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SenderRefTrackResult;
use Kwaadpepper\ChronopostApiPhp\Enums\Locale;
use Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError;
use Kwaadpepper\ChronopostApiPhp\Exceptions\Tracking\TrackingException;
use Kwaadpepper\ChronopostApiPhp\Factory\TrackingSkybillV2EventFactory;
use Kwaadpepper\ChronopostApiPhp\Factory\TrackSearchResultFactory;
use Kwaadpepper\ChronopostApiPhp\Factory\TrackWithSenderRefFactory;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingV2Locale;
use WsdlToPhp\PackageBase\SoapClientInterface;

class TrackSearchService implements TrackingServiceInterface
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
     * @param array<string, mixed>                       $soapOptions  Additional options for the soap client.
     * @param \ChronopostTracking\ServiceType\Track|null $trackService Injected track service (for testing).
     *
     * @phpcs:disable Generic.Files.LineLength.TooLong
     */
    public function __construct(
        #[\SensitiveParameter] private AccountNumber $accountNumber,
        #[\SensitiveParameter] private Password $password,
        array $soapOptions = [],
        ?Track $trackService = null,
    ) {
        // phpcs:enable
        if ($trackService !== null) {
            $this->trackService = $trackService;
            return;
        }

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
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingNumber        $trackingNumber The tracking number to search for.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingV2Locale|null $locale         The language for the response (default is 'fr').
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SkybillV2\EventInfo[] An array of tracking events.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError          If the API call fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Tracking\TrackingException If the tracking number is invalid or if there are no events found.
     */
    public function findUsingTrackingNumber(TrackingNumber $trackingNumber, ?TrackingV2Locale $locale = null): array
    {
        // phpcs:enable
        $locale     = $locale ?? TrackingV2Locale::create(Locale::FR);
        $parameters = new TrackSkybillV2(
            language: (string) $locale,
            skybillNumber: (string) $trackingNumber,
        );

        $result = $this->trackService->trackSkybillV2($parameters);

        if ($result === false) {
            $lastError = $this->trackService->getLastErrorForMethod(methodName: Track::class . '::trackSkybillV2');
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
            $events,
        );
    }

    /**
     * Search tracking using multiple criteria.
     *
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
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the API call fails or returns an invalid response.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Tracking\TrackingException If the API returns an error response.
     *
     * @phpcs:disable Generic.Files.LineLength.TooLong
     */
    public function trackSearch(
        ?string $consigneesCountry = null,
        ?string $consigneesRef = null,
        ?string $consigneesZipCode = null,
        ?string $dateDeposit = null,
        ?string $dateEndDeposit = null,
        ?string $parcelState = null,
        ?string $sendersRef = null,
        ?string $serviceCode = null,
        ?TrackingV2Locale $locale = null,
    ): SearchTrackResult {
        // phpcs:enable
        $locale     = $locale ?? TrackingV2Locale::create(Locale::FR);
        $parameters = new TrackSearch(
            accountNumber: $this->accountNumber->getAccountNumber(),
            password: $this->password->getPassword(),
            language: (string) $locale,
            consigneesCountry: $consigneesCountry,
            consigneesRef: $consigneesRef,
            consigneesZipCode: $consigneesZipCode,
            dateDeposit: $dateDeposit,
            dateEndDeposit: $dateEndDeposit,
            parcelState: $parcelState,
            sendersRef: $sendersRef,
            serviceCode: $serviceCode,
        );

        $result = $this->trackService->trackSearch($parameters);

        if ($result === false) {
            $lastError = $this->trackService->getLastErrorForMethod(
                methodName: Track::class . '::trackSearch',
            );
            throw new ApiError('Failed to call track search service', $lastError);
        }

        $response = $result->getReturn();

        if ($response === null) {
            throw new ApiError('Failed to get result from track search service, null response');
        }

        if ($response->getErrorCode() !== 0) {
            throw new TrackingException(
                (string) $response->getErrorMessage(),
                (int) $response->getErrorCode(),
            );
        }

        return (new TrackSearchResultFactory())->create($response);
    }

    /**
     * Track parcels using sender reference.
     *
     * @param string                                                           $senderRef
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingV2Locale|null $locale
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SenderRefTrackResult
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the API call fails or returns an invalid response.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Tracking\TrackingException If the API returns an error response.
     *
     * @phpcs:disable Generic.Files.LineLength.TooLong
     */
    public function trackWithSenderRef(
        string $senderRef,
        ?TrackingV2Locale $locale = null,
    ): SenderRefTrackResult {
        // phpcs:enable
        $locale     = $locale ?? TrackingV2Locale::create(Locale::FR);
        $parameters = new TrackWithSenderRef(
            accountNumber: $this->accountNumber->getAccountNumber(),
            password: $this->password->getPassword(),
            language: (string) $locale,
            sendersRef: $senderRef,
        );

        $result = $this->trackService->trackWithSenderRef($parameters);

        if ($result === false) {
            $lastError = $this->trackService->getLastErrorForMethod(
                methodName: Track::class . '::trackWithSenderRef',
            );
            throw new ApiError('Failed to call track with sender ref service', $lastError);
        }

        $response = $result->getReturn();

        if ($response === null) {
            throw new ApiError(
                'Failed to get result from track with sender ref service, null response',
            );
        }

        if ($response->getErrorCode() !== 0) {
            throw new TrackingException(
                (string) $response->getErrorMessage(),
                (int) $response->getErrorCode(),
            );
        }

        return (new TrackWithSenderRefFactory())->create($response);
    }

    /**
     * Track using an ESD number.
     *
     * @param string                                                           $esdNumber
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingV2Locale|null $locale
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\EsdTrackResult
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the API call fails or returns an invalid response.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Tracking\TrackingException If the API returns an error response.
     *
     * @phpcs:disable Generic.Files.LineLength.TooLong
     */
    public function trackEsd(
        string $esdNumber,
        ?TrackingV2Locale $locale = null,
    ): EsdTrackResult {
        // phpcs:enable
        $locale     = $locale ?? TrackingV2Locale::create(Locale::FR);
        $parameters = new TrackESD(
            language: (string) $locale,
            esdNumber: $esdNumber,
        );

        $result = $this->trackService->trackESD($parameters);

        if ($result === false) {
            $lastError = $this->trackService->getLastErrorForMethod(
                methodName: Track::class . '::trackESD',
            );
            throw new ApiError('Failed to call track ESD service', $lastError);
        }

        $response = $result->getReturn();

        if ($response === null) {
            throw new ApiError('Failed to get result from track ESD service, null response');
        }

        if ($response->getErrorCode() !== 0) {
            throw new TrackingException(
                (string) $response->getErrorMessage(),
                (int) $response->getErrorCode(),
            );
        }

        $events  = $response->getListEventInfoComp()?->getEvents() ?? [];
        $factory = new TrackingSkybillV2EventFactory();

        return new EsdTrackResult(
            events: array_map(
                fn (EventInfoComp $event) => $factory->create($event),
                $events,
            ),
        );
    }
}
