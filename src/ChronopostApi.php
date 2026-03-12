<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp;

use DateTime;
use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\DeliveryTime;
use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\ProductList;
use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\QuickCostV3;
use Kwaadpepper\ChronopostApiPhp\Dto\Relay\RelaySearchResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\MonoParcelV7;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\MultiParcelV4;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\ReservationMultiParcelResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\ReservationResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\CancelListResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\CancelResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\EsdTrackResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\ProofOfDelivery;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\ProofOfDeliveryByRef;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SearchTrackResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SenderRefTrackResult;
use Kwaadpepper\ChronopostApiPhp\Enums\ShippingType;
use Kwaadpepper\ChronopostApiPhp\Enums\SkyBillOutputMode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AddressSearch;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\CustomerValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\EsdValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\RecipientValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ReferenceValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ScheduledValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ShipperValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\SkyBillParameters;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\SkyBillValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ProductCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayPointType;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayServiceType;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\WantedShippingDate;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ServiceCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingNumber;
use Kwaadpepper\ChronopostApiPhp\Services\Calculate\CalculateService;
use Kwaadpepper\ChronopostApiPhp\Services\Cost\QuickCostService;
use Kwaadpepper\ChronopostApiPhp\Services\RelayPoint\RelayPointService;
use Kwaadpepper\ChronopostApiPhp\Services\Shipping\ShippingService;
use Kwaadpepper\ChronopostApiPhp\Services\Tracking\ProofOfDeliveryService;
use Kwaadpepper\ChronopostApiPhp\Services\Tracking\TrackCancelService;
use Kwaadpepper\ChronopostApiPhp\Services\Tracking\TrackSearchService;
use WsdlToPhp\PackageBase\SoapClientInterface;

class ChronopostApi
{
    private TrackSearchService $trackSearchService;

    private TrackCancelService $trackCancelService;

    private ProofOfDeliveryService $proofOfDeliveryService;

    private ShippingService $shippingService;

    private CalculateService $calculateService;

    private QuickCostService $quickCostService;

    private RelayPointService $relayPointService;

    /**
     * Constructor
     *
     * @param  \Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber $accountNumber The account number.
     * @param  \Kwaadpepper\ChronopostApiPhp\ObjectValues\Password      $password      The password.
     */
    public function __construct(
        #[\SensitiveParameter] private AccountNumber $accountNumber,
        #[\SensitiveParameter] private Password $password
    ) {
        $defaultSopapOptions = [
            SoapClientInterface::WSDL_LOGIN    => $accountNumber->getAccountNumber(),
            SoapClientInterface::WSDL_PASSWORD => $password->getPassword(),
        ];

        $this->trackSearchService     = new TrackSearchService($defaultSopapOptions);
        $this->trackCancelService     = new TrackCancelService($defaultSopapOptions);
        $this->proofOfDeliveryService = new ProofOfDeliveryService($defaultSopapOptions);
        $this->shippingService        = new ShippingService($defaultSopapOptions);
        $this->calculateService       = new CalculateService($defaultSopapOptions);
        $this->quickCostService       = new QuickCostService($defaultSopapOptions);
        $this->relayPointService      = new RelayPointService($defaultSopapOptions);
    }

    /**
     * Track a single shipment using the tracking number.
     *
     * @param  \Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingNumber $trackingNumber The tracking number to search.
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SkybillV2\EventInfo[] The tracking information.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the API call fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Tracking\TrackingException If the tracking number is invalid
     *                                                                    or if there are no events found.
     */
    public function trackSingleShipment(TrackingNumber $trackingNumber): array
    {
        return $this->trackSearchService->findUsingTrackingNumber($trackingNumber);
    }

    /**
     * Calculate the delivery time for a shipment.
     *
     * @param  \Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode    $from         The sender's postal code.
     * @param  \Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode    $to           The recipient's postal code.
     * @param  string                                                 $toCityName   The recipient's city name.
     * @param  \Kwaadpepper\ChronopostApiPhp\ObjectValues\ProductCode $productCode  The product code for the shipment.
     * @param  \Kwaadpepper\ChronopostApiPhp\Enums\ShippingType       $shippingType The shipping type.
     * @param  \Kwaadpepper\ChronopostApiPhp\ObjectValues\ServiceCode $serviceCode  The service code for the shipment.
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\DeliveryTime The delivery time information.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the API call fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Calculate\CalculateException If the API returns an error.
     */
    public function calculateDeliveryTime(
        PostCode $from,
        PostCode $to,
        string $toCityName,
        ProductCode $productCode,
        ShippingType $shippingType,
        ServiceCode $serviceCode
    ): DeliveryTime {
        return $this->calculateService->calculateDeliveryTime(
            $from,
            $to,
            $toCityName,
            $productCode,
            $shippingType,
            $serviceCode
        );
    }

    /**
     * Calculate possible products for a shipment.
     *
     * @param  \Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode $from         The sender's postal code.
     * @param  \Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode $to           The recipient's postal code.
     * @param  \Kwaadpepper\ChronopostApiPhp\Enums\ShippingType    $shippingType The shipping type.
     * @param  float                                               $weight       The weight of the shipment in kilograms.
     * @param  float|null                                          $height       The height of the shipment in centimeters.
     * @param  float|null                                          $length       The length of the shipment in centimeters.
     * @param  float|null                                          $width        The width of the shipment in centimeters.
     * @param  \DateTime|null                                      $shippingDate The desired shipping date.
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\ProductList The list of possible products.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the API call fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Calculate\CalculateException If the API returns an error.
     */
    public function calculatePossibleProductsForShipping(
        PostCode $from,
        PostCode $to,
        string $toCityName,
        ShippingType $shippingType,
        float $weight,
        ?float $height = null,
        ?float $length = null,
        ?float $width = null,
        ?DateTime $shippingDate = null
    ): ProductList {
        return $this->calculateService->calculateProducts(
            $this->accountNumber,
            $this->password,
            $from,
            $to,
            $toCityName,
            $shippingType,
            $weight,
            $height,
            $length,
            $width,
            $shippingDate
        );
    }

    /**
     * Estimate the shipping cost for a shipment.
     *
     * @param  \Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode    $from          The sender's postal code.
     * @param  \Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode    $to            The recipient's postal code.
     * @param  integer                                                $weightInGrams The weight of the shipment in grams.
     * @param  \Kwaadpepper\ChronopostApiPhp\ObjectValues\ProductCode $productCode   The product code for the shipment.
     * @param  \Kwaadpepper\ChronopostApiPhp\Enums\ShippingType       $shippingType  The shipping type.
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\QuickCostV3 The estimated shipping cost.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the API call fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\QuickCost\QuickCostException If the API returns an error.
     */
    public function estimateShippingCost(
        PostCode $from,
        PostCode $to,
        int $weightInGrams,
        ProductCode $productCode,
        ShippingType $shippingType
    ): QuickCostV3 {
        return $this->quickCostService->quickCostV3(
            $this->accountNumber,
            $this->password,
            $from,
            $to,
            $weightInGrams / 1000,
            $productCode,
            $shippingType
        );
    }

    /**
     * Creates a single-parcel shipment with the provided values.
     *
     * @throws \InvalidArgumentException If the provided values are invalid.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the API call fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException If the shipping operation fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\EsdException If the ESD operation fails.
     */
    public function singleParcelV4(
        SkyBillValue $skybillValue,
        CustomerValue $customerValue,
        ShipperValue $shipperValue,
        RecipientValue $recipientValue,
        ReferenceValue $referenceValue,
        ?ScheduledValue $scheduledValue = null,
        ?EsdValue $esdValue = null,
        SkyBillOutputMode $skyBillOutputMode = SkyBillOutputMode::NO_MAIL_SENDING,
        ?SkyBillParameters $skyBillParameters = null
    ): MultiParcelV4 {
        return $this->shippingService->multiParcelV4(
            $this->accountNumber,
            $this->password,
            skybillValues: [$skybillValue],
            customerValue: $customerValue,
            shippersValues: [$shipperValue],
            recipientsValues: [$recipientValue],
            referenceValues: [$referenceValue],
            scheduledValues: $scheduledValue ? [$scheduledValue] : [],
            esdValue: $esdValue,
            numberOfParcel: 1,
            multiParcel: false,
            skyBillOutputMode: $skyBillOutputMode,
            skyBillParameters: $skyBillParameters
        );
    }

    /**
     * Creates a single-parcel shipment with the provided values.
     *
     * @param  \Kwaadpepper\ChronopostApiPhp\ObjectValues\MultiParcelPart[] $multiParcelParts
     *
     * @throws \InvalidArgumentException If the provided values are invalid.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the API call fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException If the shipping operation fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\EsdException If the ESD operation fails.
     */
    public function multiParcelV4OneShipperToOneRecipient(
        array $multiParcelParts,
        CustomerValue $customerValue,
        ShipperValue $shipperValue,
        RecipientValue $recipientValue,
        ?EsdValue $esdValue = null,
        SkyBillOutputMode $skyBillOutputMode = SkyBillOutputMode::NO_MAIL_SENDING,
        ?SkyBillParameters $skyBillParameters = null
    ): MultiParcelV4 {
        $skybillValues   = [];
        $referenceValues = [];
        $scheduledValues = [];

        // phpcs:ignore Generic.Files.LineLength.TooLong
        array_map(function ($part) use (&$skybillValues, &$referenceValues, &$scheduledValues) {
            $skybillValues[]   = $part->skybillValue;
            $referenceValues[] = $part->referenceValue;
            if ($part->scheduledValue !== null) {
                $scheduledValues[] = $part->scheduledValue;
            }
        }, $multiParcelParts);

        return $this->shippingService->multiParcelV4(
            $this->accountNumber,
            $this->password,
            customerValue: $customerValue,
            skybillValues: $skybillValues,
            shippersValues: [$shipperValue],
            recipientsValues: [$recipientValue],
            referenceValues: $referenceValues,
            scheduledValues: $scheduledValues,
            esdValue: $esdValue,
            numberOfParcel: count($multiParcelParts),
            multiParcel: count($multiParcelParts) > 1,
            skyBillOutputMode: $skyBillOutputMode,
            skyBillParameters: $skyBillParameters
        );
    }

    /**
     * Find relay points using search criteria.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\ProductCode              $productCode        The product code for the search.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\AddressSearch            $addressSearch      The address search criteria.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\WantedShippingDate $wantedShippingDate The desired shipping date.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayPointType     $relayPointType     The type of relay point to search for (default is ANY).
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayServiceType   $relayServiceType   The type of relay service to search for (default is ANY).
     * @param float|null                                                          $weight             Optional weight of the package (in kg) for filtering results.
     * @param integer|null                                                        $maxResults         Optional maximum number of results to return (default is 25, max is 25).
     * @param integer|null                                                        $radiusInKm         Optional search radius in kilometers (default is 50, max is 50).
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Relay\RelaySearchResult An array of relay points matching the search criteria.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Relay\RelaySearchException If the API returns an error response.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError          If the API call fails or returns an invalid response.
     */
    public function searchRelayPoint(
        ProductCode $productCode,
        AddressSearch $addressSearch,
        WantedShippingDate $wantedShippingDate,
        RelayPointType $relayPointType = RelayPointType::ANY,
        RelayServiceType $relayServiceType = RelayServiceType::ANY,
        ?float $weight = null,
        ?int $maxResults = null,
        ?int $radiusInKm = null
    ): RelaySearchResult {
        return $this->relayPointService->searchRelayPoint(
            $this->accountNumber,
            $this->password,
            $productCode,
            $addressSearch,
            $wantedShippingDate,
            $relayPointType,
            $relayServiceType,
            $weight,
            $maxResults,
            $radiusInKm
        );
    }

    /**
     * Cancel a single shipment.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingNumber $trackingNumber The tracking number to cancel.
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\CancelResult
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Tracking\TrackingException
     *
     * @phpcs:disable Generic.Files.LineLength.TooLong
     */
    public function cancelShipment(TrackingNumber $trackingNumber): CancelResult
    {
        // phpcs:enable
        return $this->trackCancelService->cancelSkybill(
            $this->accountNumber,
            $this->password,
            $trackingNumber,
        );
    }

    /**
     * Cancel multiple shipments.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingNumber[] $trackingNumbers
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\CancelListResult
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Tracking\TrackingException
     *
     * @phpcs:disable Generic.Files.LineLength.TooLong
     */
    public function cancelMultipleShipments(array $trackingNumbers): CancelListResult
    {
        // phpcs:enable
        return $this->trackCancelService->cancelListSkybill(
            $this->accountNumber,
            $this->password,
            $trackingNumbers,
        );
    }

    /**
     * Search tracking with multiple criteria.
     *
     * @param string|null $consigneesCountry
     * @param string|null $consigneesRef
     * @param string|null $consigneesZipCode
     * @param string|null $dateDeposit
     * @param string|null $dateEndDeposit
     * @param string|null $parcelState
     * @param string|null $sendersRef
     * @param string|null $serviceCode
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SearchTrackResult
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Tracking\TrackingException
     *
     * @phpcs:disable Generic.Files.LineLength.TooLong
     */
    public function trackBySearchQuery(
        ?string $consigneesCountry = null,
        ?string $consigneesRef = null,
        ?string $consigneesZipCode = null,
        ?string $dateDeposit = null,
        ?string $dateEndDeposit = null,
        ?string $parcelState = null,
        ?string $sendersRef = null,
        ?string $serviceCode = null,
    ): SearchTrackResult {
        // phpcs:enable
        return $this->trackSearchService->trackSearch(
            $this->accountNumber,
            $this->password,
            $consigneesCountry,
            $consigneesRef,
            $consigneesZipCode,
            $dateDeposit,
            $dateEndDeposit,
            $parcelState,
            $sendersRef,
            $serviceCode,
        );
    }

    /**
     * Track parcels using sender reference.
     *
     * @param string $senderRef The sender reference.
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SenderRefTrackResult
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Tracking\TrackingException
     *
     * @phpcs:disable Generic.Files.LineLength.TooLong
     */
    public function trackBySenderReference(string $senderRef): SenderRefTrackResult
    {
        // phpcs:enable
        return $this->trackSearchService->trackWithSenderRef(
            $this->accountNumber,
            $this->password,
            $senderRef,
        );
    }

    /**
     * Track using an ESD number.
     *
     * @param string $esdNumber The ESD number to track.
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\EsdTrackResult
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Tracking\TrackingException
     *
     * @phpcs:disable Generic.Files.LineLength.TooLong
     */
    public function trackEsd(string $esdNumber): EsdTrackResult
    {
        // phpcs:enable
        return $this->trackSearchService->trackEsd($esdNumber);
    }

    /**
     * Search for proof of delivery by tracking number.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingNumber $trackingNumber
     * @param boolean                                                   $pdf            Request PDF format.
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\ProofOfDelivery
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Tracking\TrackingException
     *
     * @phpcs:disable Generic.Files.LineLength.TooLong
     */
    public function searchProofOfDelivery(
        TrackingNumber $trackingNumber,
        bool $pdf = true,
    ): ProofOfDelivery {
        // phpcs:enable
        return $this->proofOfDeliveryService->searchPod(
            $this->accountNumber,
            $this->password,
            $trackingNumber,
            $pdf,
        );
    }

    /**
     * Search for proof of delivery by sender reference.
     *
     * @param string  $senderRef The sender reference.
     * @param boolean $pdf       Request PDF format.
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\ProofOfDeliveryByRef
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Tracking\TrackingException
     *
     * @phpcs:disable Generic.Files.LineLength.TooLong
     */
    public function searchProofOfDeliveryBySenderRef(
        string $senderRef,
        bool $pdf = true,
    ): ProofOfDeliveryByRef {
        // phpcs:enable
        return $this->proofOfDeliveryService->searchPodWithSenderRef(
            $this->accountNumber,
            $this->password,
            $senderRef,
            $pdf,
        );
    }

    /**
     * Creates a single-parcel shipment using the V7 API.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the API call fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException If the shipping operation fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\EsdException If the ESD operation fails.
     */
    public function singleParcelV7(
        SkyBillValue $skybillValue,
        CustomerValue $customerValue,
        ShipperValue $shipperValue,
        RecipientValue $recipientValue,
        ReferenceValue $referenceValue,
        ?ScheduledValue $scheduledValue = null,
        ?EsdValue $esdValue = null,
        SkyBillOutputMode $skyBillOutputMode = SkyBillOutputMode::NO_MAIL_SENDING,
        ?SkyBillParameters $skyBillParameters = null
    ): MonoParcelV7 {
        return $this->shippingService->singleParcelV7(
            $this->accountNumber,
            $this->password,
            $skybillValue,
            $customerValue,
            $shipperValue,
            $recipientValue,
            $referenceValue,
            $scheduledValue,
            $esdValue,
            $skyBillOutputMode,
            $skyBillParameters,
        );
    }

    /**
     * Creates a multi-parcel shipment using the V7 API.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\SkyBillValue[]   $skybillValues
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ShipperValue[]   $shippersValues
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\RecipientValue[] $recipientsValues
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ReferenceValue[] $referenceValues
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ScheduledValue[] $scheduledValues
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the API call fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException If the shipping operation fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\EsdException If the ESD operation fails.
     */
    public function multiParcelV7(
        array $skybillValues,
        CustomerValue $customerValue,
        array $shippersValues,
        array $recipientsValues,
        array $referenceValues = [],
        array $scheduledValues = [],
        ?EsdValue $esdValue = null,
        int $numberOfParcel = 1,
        bool $multiParcel = false,
        SkyBillOutputMode $skyBillOutputMode = SkyBillOutputMode::NO_MAIL_SENDING,
        ?SkyBillParameters $skyBillParameters = null
    ): MultiParcelV4 {
        return $this->shippingService->multiParcelV7(
            $this->accountNumber,
            $this->password,
            $skybillValues,
            $customerValue,
            $shippersValues,
            $recipientsValues,
            $referenceValues,
            $scheduledValues,
            $esdValue,
            $numberOfParcel,
            $multiParcel,
            $skyBillOutputMode,
            $skyBillParameters,
        );
    }

    /**
     * Creates a single-parcel shipment with reservation.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the API call fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException If the shipping operation fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\EsdException If the ESD operation fails.
     */
    public function singleParcelWithReservation(
        SkyBillValue $skybillValue,
        CustomerValue $customerValue,
        ShipperValue $shipperValue,
        RecipientValue $recipientValue,
        ReferenceValue $referenceValue,
        ?EsdValue $esdValue = null,
        ?ScheduledValue $scheduledValue = null,
        SkyBillOutputMode $skyBillOutputMode = SkyBillOutputMode::NO_MAIL_SENDING,
        ?SkyBillParameters $skyBillParameters = null
    ): ReservationResult {
        return $this->shippingService->singleParcelWithReservation(
            $this->accountNumber,
            $this->password,
            $skybillValue,
            $customerValue,
            $shipperValue,
            $recipientValue,
            $referenceValue,
            $esdValue,
            $scheduledValue,
            $skyBillOutputMode,
            $skyBillParameters,
        );
    }

    /**
     * Creates a multi-parcel shipment with reservation.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\SkyBillValue[]   $skybillValues
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\RecipientValue[] $recipientsValues
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ReferenceValue[] $referenceValues
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the API call fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException If the shipping operation fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\EsdException If the ESD operation fails.
     */
    public function multiParcelWithReservation(
        array $skybillValues,
        CustomerValue $customerValue,
        ShipperValue $shipperValue,
        array $recipientsValues,
        array $referenceValues = [],
        ?EsdValue $esdValue = null,
        ?ScheduledValue $scheduledValue = null,
        int $numberOfParcel = 1,
        bool $multiParcel = false,
        SkyBillOutputMode $skyBillOutputMode = SkyBillOutputMode::NO_MAIL_SENDING,
        ?SkyBillParameters $skyBillParameters = null
    ): ReservationMultiParcelResult {
        return $this->shippingService->multiParcelWithReservation(
            $this->accountNumber,
            $this->password,
            $skybillValues,
            $customerValue,
            $shipperValue,
            $recipientsValues,
            $referenceValues,
            $esdValue,
            $scheduledValue,
            $numberOfParcel,
            $multiParcel,
            $skyBillOutputMode,
            $skyBillParameters,
        );
    }

    /**
     * Creates a shipment with ESD only (no transport ticket).
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the API call fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException If the shipping operation fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\EsdException If the ESD operation fails.
     */
    public function shippingWithEsdOnly(
        SkyBillValue $skybillValue,
        CustomerValue $customerValue,
        ShipperValue $shipperValue,
        RecipientValue $recipientValue,
        ReferenceValue $referenceValue,
        EsdValue $esdValue,
        SkyBillOutputMode $skyBillOutputMode = SkyBillOutputMode::NO_MAIL_SENDING,
        ?SkyBillParameters $skyBillParameters = null
    ): ReservationResult {
        return $this->shippingService->shippingWithEsdOnly(
            $this->accountNumber,
            $this->password,
            $skybillValue,
            $customerValue,
            $shipperValue,
            $recipientValue,
            $referenceValue,
            $esdValue,
            $skyBillOutputMode,
            $skyBillParameters,
        );
    }

    /**
     * Creates a shipment with reservation and ESD with client reference.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the API call fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException If the shipping operation fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\EsdException If the ESD operation fails.
     */
    public function shippingWithReservationAndEsd(
        SkyBillValue $skybillValue,
        CustomerValue $customerValue,
        ShipperValue $shipperValue,
        RecipientValue $recipientValue,
        ReferenceValue $referenceValue,
        EsdValue $esdValue,
        SkyBillOutputMode $skyBillOutputMode = SkyBillOutputMode::NO_MAIL_SENDING
    ): ReservationResult {
        return $this->shippingService->shippingWithReservationAndEsd(
            $this->accountNumber,
            $this->password,
            $skybillValue,
            $customerValue,
            $shipperValue,
            $recipientValue,
            $referenceValue,
            $esdValue,
            $skyBillOutputMode,
        );
    }
}
