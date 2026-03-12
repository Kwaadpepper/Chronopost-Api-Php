<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp;

use ChronopostShipping\StructType\RecipientValue as RecipientValueChronopost;
use ChronopostShipping\StructType\ShipperValue as ShipperValueChronopost;
use ChronopostShipping\StructType\SkybillValueBase;
use Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot\DeliverySlotConfirmation;
use Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot\DeliverySlotSearchResult;
use Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot\GeocodingResult;
use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\DeliveryTime;
use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\ProductCatalog;
use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\ProductList;
use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\QuickCostV3;
use Kwaadpepper\ChronopostApiPhp\Dto\Relay\RelaySearchResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\CancelPickupResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\MonoParcelV7;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\MultiParcelV4;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\PickupConstraints;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\PickupCreationResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\PickupFeasibility;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\RoutingInfo;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\ReservationMultiParcelResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\ReservationResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\ShippingInformation;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\SkybillLabel;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\CancelListResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\CancelResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\EsdTrackResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\ProofOfDelivery;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\ProofOfDeliveryByRef;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SearchTrackResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SenderRefTrackResult;
use Kwaadpepper\ChronopostApiPhp\Enums\CountryForChronopost;
use Kwaadpepper\ChronopostApiPhp\Enums\ShippingType;
use Kwaadpepper\ChronopostApiPhp\Enums\SkyBillOutputMode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AddressSearch;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\GeocodingAddress;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\CustomerValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\EsdValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\RecipientValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ReferenceValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ScheduledValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ShipperValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\SkyBillParameters;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\SkyBillValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Coordinates;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\DpdRecipients;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\EsdParticularities;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\OrderGiver;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\PickupAddress;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\PickupHeader;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\PickupOptions;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\PickupShipper;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PickupSearchCriteria;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ProductCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayId;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayPointType;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayServiceType;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\WantedShippingDate;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\RoutingQuery;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ServiceCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ShippingEstimateRequest;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\SlotConfirmRequest;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\SlotSearchCriteria;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingSearchCriteria;
use Kwaadpepper\ChronopostApiPhp\Services\Calculate\CalculateService;
use Kwaadpepper\ChronopostApiPhp\Services\Cost\QuickCostService;
use Kwaadpepper\ChronopostApiPhp\Services\DeliverySlot\DeliverySlotService;
use Kwaadpepper\ChronopostApiPhp\Services\RelayPoint\RelayPointService;
use Kwaadpepper\ChronopostApiPhp\Services\Shipping\PickupService;
use Kwaadpepper\ChronopostApiPhp\Services\Shipping\ShippingLabelService;
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

    private ShippingLabelService $shippingLabelService;

    private CalculateService $calculateService;

    private QuickCostService $quickCostService;

    private RelayPointService $relayPointService;

    private PickupService $pickupService;

    private DeliverySlotService $deliverySlotService;

    /**
     * Constructor
     *
     * @param  \Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber $accountNumber The account number.
     * @param  \Kwaadpepper\ChronopostApiPhp\ObjectValues\Password      $password      The password.
     */
    public function __construct(
        #[\SensitiveParameter] AccountNumber $accountNumber,
        #[\SensitiveParameter] Password $password,
    ) {
        $defaultSopapOptions = [
            SoapClientInterface::WSDL_LOGIN    => $accountNumber->getAccountNumber(),
            SoapClientInterface::WSDL_PASSWORD => $password->getPassword(),
        ];

        $this->trackSearchService     = new TrackSearchService($accountNumber, $password, $defaultSopapOptions);
        $this->trackCancelService     = new TrackCancelService($accountNumber, $password, $defaultSopapOptions);
        $this->proofOfDeliveryService = new ProofOfDeliveryService($accountNumber, $password, $defaultSopapOptions);
        $this->shippingService        = new ShippingService($accountNumber, $password, $defaultSopapOptions);
        $this->shippingLabelService   = new ShippingLabelService($accountNumber, $password, $defaultSopapOptions);
        $this->calculateService       = new CalculateService($accountNumber, $password, $defaultSopapOptions);
        $this->quickCostService       = new QuickCostService($accountNumber, $password, $defaultSopapOptions);
        $this->relayPointService      = new RelayPointService($accountNumber, $password, $defaultSopapOptions);
        $this->pickupService          = new PickupService($accountNumber, $password, $defaultSopapOptions);
        $this->deliverySlotService    = new DeliverySlotService($accountNumber, $password, $defaultSopapOptions);
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
    public function trackShipment(TrackingNumber $trackingNumber): array
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
        ServiceCode $serviceCode,
    ): DeliveryTime {
        return $this->calculateService->calculateDeliveryTime(
            $from,
            $to,
            $toCityName,
            $productCode,
            $shippingType,
            $serviceCode,
        );
    }

    /**
     * Calculate possible products for a shipment.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the API call fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Calculate\CalculateException If the API returns an error.
     */
    public function calculatePossibleProductsForShipping(
        ShippingEstimateRequest $request,
    ): ProductList {
        return $this->calculateService->calculateProducts(
            $request,
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
    public function calculateShippingCost(
        PostCode $from,
        PostCode $to,
        int $weightInGrams,
        ProductCode $productCode,
        ShippingType $shippingType,
    ): QuickCostV3 {
        return $this->quickCostService->quickCostV3(
            $from,
            $to,
            $weightInGrams / 1000,
            $productCode,
            $shippingType,
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
        ?SkyBillParameters $skyBillParameters = null,
    ): MultiParcelV4 {
        return $this->shippingService->multiParcelV4(
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
            skyBillParameters: $skyBillParameters,
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
        ?SkyBillParameters $skyBillParameters = null,
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
            skyBillParameters: $skyBillParameters,
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
        ?int $radiusInKm = null,
    ): RelaySearchResult {
        return $this->relayPointService->searchRelayPoint(
            $productCode,
            $addressSearch,
            $wantedShippingDate,
            $relayPointType,
            $relayServiceType,
            $weight,
            $maxResults,
            $radiusInKm,
        );
    }

    /**
     * Find relay points by GPS coordinates.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Coordinates              $coordinates        GPS coordinates.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\ProductCode              $productCode        The product code.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\WantedShippingDate $wantedShippingDate The desired shipping date.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayPointType     $relayPointType     The relay point type.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayServiceType   $relayServiceType   The relay service type.
     * @param float|null                                                          $weight             Weight in kg.
     * @param integer|null                                                        $maxResults         Max results (1-25).
     * @param integer|null                                                        $radiusInKm         Search radius (1-50 km).
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Relay\RelaySearchResult
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Relay\RelaySearchException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     */
    public function searchRelayPointByCoordinates(
        Coordinates $coordinates,
        ProductCode $productCode,
        WantedShippingDate $wantedShippingDate,
        RelayPointType $relayPointType = RelayPointType::ANY,
        RelayServiceType $relayServiceType = RelayServiceType::ANY,
        ?float $weight = null,
        ?int $maxResults = null,
        ?int $radiusInKm = null,
    ): RelaySearchResult {
        return $this->relayPointService->searchRelayPointByCoordinates(
            $coordinates,
            $productCode,
            $wantedShippingDate,
            $relayPointType,
            $relayServiceType,
            $weight,
            $maxResults,
            $radiusInKm,
        );
    }

    /**
     * Find a relay point by its unique identifier.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayId $relayId The relay point identifier.
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Relay\RelayPointBasic[]
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     */
    public function searchRelayPointById(RelayId $relayId): array
    {
        return $this->relayPointService->searchRelayPointById($relayId);
    }

    /**
     * Get detailed information about a relay point.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayId $relayId The relay point identifier.
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Relay\RelaySearchResult
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Relay\RelaySearchException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     */
    public function getRelayPointDetail(RelayId $relayId): RelaySearchResult
    {
        return $this->relayPointService->getRelayPointDetail(
            $relayId,
        );
    }

    /**
     * Get detailed information about an international relay point.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayId      $relayId  The relay point identifier.
     * @param \Kwaadpepper\ChronopostApiPhp\Enums\CountryForChronopost      $country  The country.
     * @param string                                                        $language Language code (default 'FR').
     * @param string                                                        $version  API version (default '2.0').
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Relay\RelaySearchResult
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Relay\RelaySearchException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     */
    public function getInternationalRelayPointDetail(
        RelayId $relayId,
        CountryForChronopost $country,
        string $language = 'FR',
        string $version = '2.0',
    ): RelaySearchResult {
        return $this->relayPointService->getInternationalRelayPointDetail(
            $relayId,
            $country,
            $language,
            $version,
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
            $trackingNumbers,
        );
    }

    /**
     * Search tracking with multiple criteria.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Tracking\TrackingException
     */
    public function trackBySearchQuery(
        TrackingSearchCriteria $criteria,
    ): SearchTrackResult {
        return $this->trackSearchService->trackSearch(
            $criteria,
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
    public function getProofOfDelivery(
        TrackingNumber $trackingNumber,
        bool $pdf = true,
    ): ProofOfDelivery {
        // phpcs:enable
        return $this->proofOfDeliveryService->searchPod(
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
    public function getProofOfDeliveryByReference(
        string $senderRef,
        bool $pdf = true,
    ): ProofOfDeliveryByRef {
        // phpcs:enable
        return $this->proofOfDeliveryService->searchPodWithSenderRef(
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
        ?SkyBillParameters $skyBillParameters = null,
    ): MonoParcelV7 {
        return $this->shippingService->singleParcelV7(
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
        ?SkyBillParameters $skyBillParameters = null,
    ): MultiParcelV4 {
        return $this->shippingService->multiParcelV7(
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
        ?SkyBillParameters $skyBillParameters = null,
    ): ReservationResult {
        return $this->shippingService->singleParcelWithReservation(
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
        ?SkyBillParameters $skyBillParameters = null,
    ): ReservationMultiParcelResult {
        return $this->shippingService->multiParcelWithReservation(
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
        ?SkyBillParameters $skyBillParameters = null,
    ): ReservationResult {
        return $this->shippingService->shippingWithEsdOnly(
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
        SkyBillOutputMode $skyBillOutputMode = SkyBillOutputMode::NO_MAIL_SENDING,
    ): ReservationResult {
        return $this->shippingService->shippingWithReservationAndEsd(
            $skybillValue,
            $customerValue,
            $shipperValue,
            $recipientValue,
            $referenceValue,
            $esdValue,
            $skyBillOutputMode,
        );
    }

    /**
     * Get a transport label from a skybill number.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException
     */
    public function getShippingLabel(
        string $numberSearch,
        string $mode = 'PDF',
        ?string $key = null,
    ): SkybillLabel {
        return $this->shippingLabelService->getSkybill(
            $numberSearch,
            $mode,
            $key,
        );
    }

    /**
     * Get a reserved transport label from reservation number.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException
     */
    public function getReservedShippingLabel(
        string $reservationNumber,
        string $mode = 'PDF',
    ): SkybillLabel {
        return $this->shippingLabelService->getReservedSkybillWithTypeAndModeByReservation(
            $reservationNumber,
            $mode,
        );
    }

    /**
     * Get routing information for a destination.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException
     */
    public function getRouting(
        RoutingQuery $query,
    ): RoutingInfo {
        return $this->shippingLabelService->getRouting(
            $query,
        );
    }

    /**
     * Get shipping information for a shipment context.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException
     */
    public function getShippingInformation(
        ShipperValueChronopost $shipperValue,
        RecipientValueChronopost $recipientValue,
        SkybillValueBase $skybillValueBase,
    ): ShippingInformation {
        return $this->shippingLabelService->getShippingInformation(
            $shipperValue,
            $recipientValue,
            $skybillValueBase,
        );
    }

    /**
     * Check if a pickup (ESD) is feasible.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\PickupException
     */
    public function checkPickupFeasibility(
        PickupShipper $shipper,
        string $retrievalDateTime,
        string $closingDateTime,
    ): PickupFeasibility {
        return $this->pickupService->checkFeasibility(
            $shipper,
            $retrievalDateTime,
            $closingDateTime,
        );
    }

    /**
     * Search pickup constraints for a location.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\PickupException
     */
    public function searchPickupConstraints(
        PickupSearchCriteria $criteria,
    ): PickupConstraints {
        return $this->pickupService->searchConstraints(
            $criteria,
        );
    }

    /**
     * Create a national pickup (ESD).
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\PickupException
     *
     * @phpcs:disable Generic.Files.LineLength.TooLong
     */
    public function createNationalPickup(
        PickupHeader $header,
        string $datePassage,
        string $datePassageFermeture,
        OrderGiver $orderGiver,
        PickupAddress $pickupAddress,
        ?EsdParticularities $esdParticularities = null,
        ?string $referenceEsdClient = null,
        ?string $contenu = null,
        ?PickupOptions $options = null,
        ?string $locale = null,
    ): PickupCreationResult {
        // phpcs:enable
        return $this->pickupService->createNationalPickup(
            $header,
            $datePassage,
            $datePassageFermeture,
            $orderGiver,
            $pickupAddress,
            $esdParticularities,
            $referenceEsdClient,
            $contenu,
            $options,
            $locale,
        );
    }

    /**
     * Create a European pickup (ESD).
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\PickupException
     *
     * @phpcs:disable Generic.Files.LineLength.TooLong
     */
    public function createEuropeanPickup(
        PickupHeader $header,
        string $datePassage,
        OrderGiver $orderGiver,
        PickupAddress $pickupAddress,
        ?DpdRecipients $dpdRecipients = null,
        ?string $locale = null,
    ): PickupCreationResult {
        // phpcs:enable
        return $this->pickupService->createEuropeanPickup(
            $header,
            $datePassage,
            $orderGiver,
            $pickupAddress,
            $dpdRecipients,
            $locale,
        );
    }

    /**
     * Cancel one or more pickups (ESD).
     *
     * @param string[] $esdNumbers
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\PickupException
     */
    public function cancelPickups(
        array $esdNumbers,
        ?string $locale = null,
    ): CancelPickupResult {
        return $this->pickupService->cancelPickups(
            $esdNumbers,
            $locale,
        );
    }

    /**
     * Calculate possible products for a shipment (V2, with caller token).
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the API call fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Calculate\CalculateException If the API returns an error.
     */
    public function calculatePossibleProductsForShippingV2(
        string $caller,
        ShippingEstimateRequest $request,
        ?string $nationalite = null,
        ?string $isPart = null,
    ): ProductList {
        return $this->calculateService->calculateProductsV2(
            $caller,
            $request,
            $nationalite,
            $isPart,
        );
    }

    /**
     * Get available products for a route (without pricing).
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the API call fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\QuickCost\QuickCostException If the API returns an error.
     */
    public function getAvailableProducts(
        ShippingEstimateRequest $request,
    ): ProductCatalog {
        return $this->quickCostService->getProducts(
            $request,
        );
    }

    /**
     * Search for available delivery time slots.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\DeliverySlot\DeliverySlotException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     */
    public function searchDeliverySlots(
        SlotSearchCriteria $criteria,
    ): DeliverySlotSearchResult {
        return $this->deliverySlotService->searchDeliverySlots(
            $criteria,
        );
    }

    /**
     * Confirm a delivery time slot.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\DeliverySlot\DeliverySlotException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     */
    public function confirmDeliverySlot(
        SlotConfirmRequest $request,
    ): DeliverySlotConfirmation {
        return $this->deliverySlotService->confirmDeliverySlot(
            $request,
        );
    }

    /**
     * Geocode an address to get coordinates.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\DeliverySlot\DeliverySlotException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     */
    public function geocodeAddress(
        GeocodingAddress $address,
    ): GeocodingResult {
        return $this->deliverySlotService->geocodeAddress(
            $address,
        );
    }
}
