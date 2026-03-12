<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Services\Tracking;

use ChronopostTracking\ClassMap;
use ChronopostTracking\ServiceType\Search;
use ChronopostTracking\StructType\SearchPOD;
use ChronopostTracking\StructType\SearchPODWithSenderRef;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\ProofOfDelivery;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\ProofOfDeliveryByRef;
use Kwaadpepper\ChronopostApiPhp\Enums\Locale;
use Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError;
use Kwaadpepper\ChronopostApiPhp\Exceptions\Tracking\TrackingException;
use Kwaadpepper\ChronopostApiPhp\Factory\ProofOfDeliveryByRefFactory;
use Kwaadpepper\ChronopostApiPhp\Factory\ProofOfDeliveryFactory;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingV2Locale;
use WsdlToPhp\PackageBase\SoapClientInterface;

class ProofOfDeliveryService
{
    private Search $searchService;

    /**
     * Tracking service soap url
     *
     * @var string
     */
    protected static string $serviceUrl = 'https://ws.chronopost.fr/tracking-cxf/TrackingServiceWS?wsdl';

    /**
     * Constructor
     *
     * @param array<string, mixed>                        $soapOptions   Additional options for the soap client.
     * @param \ChronopostTracking\ServiceType\Search|null $searchService Injected search service (for testing).
     *
     * @phpcs:disable Generic.Files.LineLength.TooLong
     */
    public function __construct(
        array $soapOptions = [],
        ?Search $searchService = null,
    ) {
        // phpcs:enable
        if ($searchService !== null) {
            $this->searchService = $searchService;
            return;
        }

        $soapOptions = array_merge(
            $soapOptions,
            [
                SoapClientInterface::WSDL_URL => static::$serviceUrl,
                SoapClientInterface::WSDL_CLASSMAP => ClassMap::get(),
            ],
        );

        $this->searchService = new Search($soapOptions);
    }

    /**
     * Search for a proof of delivery by tracking number.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber         $accountNumber
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Password              $password
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingNumber        $trackingNumber
     * @param boolean                                                          $pdf
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingV2Locale|null $locale
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\ProofOfDelivery
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the API call fails or returns an invalid response.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Tracking\TrackingException If the API returns an error response.
     *
     * @phpcs:disable Generic.Files.LineLength.TooLong
     */
    public function searchPod(
        AccountNumber $accountNumber,
        Password $password,
        TrackingNumber $trackingNumber,
        bool $pdf = true,
        ?TrackingV2Locale $locale = null,
    ): ProofOfDelivery {
        // phpcs:enable
        $locale     = $locale ?? TrackingV2Locale::create(Locale::FR);
        $parameters = new SearchPOD(
            accountNumber: $accountNumber->getAccountNumber(),
            password: $password->getPassword(),
            language: (string) $locale,
            skybillNumber: (string) $trackingNumber,
            pdf: $pdf,
        );

        $result = $this->searchService->searchPOD($parameters);

        if ($result === false) {
            $lastError = $this->searchService->getLastErrorForMethod(
                methodName: Search::class . '::searchPOD',
            );
            throw new ApiError('Failed to call search POD service', $lastError);
        }

        $response = $result->getReturn();

        if ($response === null) {
            throw new ApiError('Failed to get result from search POD service, null response');
        }

        if ($response->getErrorCode() !== 0) {
            throw new TrackingException(
                (string) $response->getErrorMessage(),
                (int) $response->getErrorCode(),
            );
        }

        return (new ProofOfDeliveryFactory())->create($response);
    }

    /**
     * Search for proof of delivery by sender reference.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber         $accountNumber
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Password              $password
     * @param string                                                           $senderRef
     * @param boolean                                                          $pdf
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingV2Locale|null $locale
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\ProofOfDeliveryByRef
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the API call fails or returns an invalid response.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Tracking\TrackingException If the API returns an error response.
     *
     * @phpcs:disable Generic.Files.LineLength.TooLong
     */
    public function searchPodWithSenderRef(
        AccountNumber $accountNumber,
        Password $password,
        string $senderRef,
        bool $pdf = true,
        ?TrackingV2Locale $locale = null,
    ): ProofOfDeliveryByRef {
        // phpcs:enable
        $locale     = $locale ?? TrackingV2Locale::create(Locale::FR);
        $parameters = new SearchPODWithSenderRef(
            accountNumber: $accountNumber->getAccountNumber(),
            password: $password->getPassword(),
            language: (string) $locale,
            sendersRef: $senderRef,
            pdf: $pdf,
        );

        $result = $this->searchService->searchPODWithSenderRef($parameters);

        if ($result === false) {
            $lastError = $this->searchService->getLastErrorForMethod(
                methodName: Search::class . '::searchPODWithSenderRef',
            );
            throw new ApiError('Failed to call search POD with sender ref service', $lastError);
        }

        $response = $result->getReturn();

        if ($response === null) {
            throw new ApiError(
                'Failed to get result from search POD with sender ref service, null response',
            );
        }

        if ($response->getErrorCode() !== 0) {
            throw new TrackingException(
                (string) $response->getErrorMessage(),
                (int) $response->getErrorCode(),
            );
        }

        return (new ProofOfDeliveryByRefFactory())->create($response);
    }
}
