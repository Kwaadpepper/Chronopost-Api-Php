<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp;

use DateTime;
use ChronopostShipping\StructType\AdresseEnlevementV3;
use ChronopostShipping\StructType\DestinatairesDpd;
use ChronopostShipping\StructType\DonneurDOrdre;
use ChronopostShipping\StructType\HeaderValue;
use ChronopostShipping\StructType\Options;
use ChronopostShipping\StructType\ParticularitesEsd;
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
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ProductCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayId;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayPointType;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayServiceType;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\WantedShippingDate;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ServiceCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingNumber;
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
        #[\SensitiveParameter] private AccountNumber $accountNumber,
        #[\SensitiveParameter] private Password $password,
    ) {
        $defaultSopapOptions = [
            SoapClientInterface::WSDL_LOGIN    => $accountNumber->getAccountNumber(),
            SoapClientInterface::WSDL_PASSWORD => $password->getPassword(),
        ];

        $this->trackSearchService     = new TrackSearchService($defaultSopapOptions);
        $this->trackCancelService     = new TrackCancelService($defaultSopapOptions);
        $this->proofOfDeliveryService = new ProofOfDeliveryService($defaultSopapOptions);
        $this->shippingService        = new ShippingService($defaultSopapOptions);
        $this->shippingLabelService   = new ShippingLabelService($defaultSopapOptions);
        $this->calculateService       = new CalculateService($defaultSopapOptions);
        $this->quickCostService       = new QuickCostService($defaultSopapOptions);
        $this->relayPointService      = new RelayPointService($defaultSopapOptions);
        $this->pickupService          = new PickupService($defaultSopapOptions);
        $this->deliverySlotService    = new DeliverySlotService($defaultSopapOptions);
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
        ?DateTime $shippingDate = null,
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
            $shippingDate,
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
        ShippingType $shippingType,
    ): QuickCostV3 {
        return $this->quickCostService->quickCostV3(
            $this->accountNumber,
            $this->password,
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
            $this->accountNumber,
            $this->password,
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
            $this->accountNumber,
            $this->password,
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
            $this->accountNumber,
            $this->password,
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
            $this->accountNumber,
            $this->password,
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
        ?SkyBillParameters $skyBillParameters = null,
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
        ?SkyBillParameters $skyBillParameters = null,
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
        ?SkyBillParameters $skyBillParameters = null,
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
        ?SkyBillParameters $skyBillParameters = null,
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
        ?SkyBillParameters $skyBillParameters = null,
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
        SkyBillOutputMode $skyBillOutputMode = SkyBillOutputMode::NO_MAIL_SENDING,
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
            $this->accountNumber,
            $this->password,
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
            $this->accountNumber,
            $this->password,
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
        string $shipperDepot,
        string $countryCode,
        string $zipCode,
        ?string $socode = null,
        ?string $ascode = null,
    ): RoutingInfo {
        return $this->shippingLabelService->getRouting(
            $this->accountNumber,
            $this->password,
            $shipperDepot,
            $countryCode,
            $zipCode,
            $socode,
            $ascode,
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
            $this->accountNumber,
            $this->password,
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
        ShipperValueChronopost $shipperValue,
        string $retrievalDateTime,
        string $closingDateTime,
    ): PickupFeasibility {
        return $this->pickupService->checkFeasibility(
            $shipperValue,
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
        string $country,
        string $zipCode,
        string $city,
    ): PickupConstraints {
        return $this->pickupService->searchConstraints(
            $this->accountNumber,
            $this->password,
            $country,
            $zipCode,
            $city,
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
        HeaderValue $headerValue,
        string $datePassage,
        string $datePassageFermeture,
        DonneurDOrdre $donneurDOrdre,
        AdresseEnlevementV3 $adresseEnlevement,
        ?ParticularitesEsd $particularitesEsd = null,
        ?string $referenceEsdClient = null,
        ?string $contenu = null,
        ?Options $options = null,
        ?string $locale = null,
    ): PickupCreationResult {
        // phpcs:enable
        return $this->pickupService->createNationalPickup(
            $this->accountNumber,
            $this->password,
            $headerValue,
            $datePassage,
            $datePassageFermeture,
            $donneurDOrdre,
            $adresseEnlevement,
            $particularitesEsd,
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
        HeaderValue $headerValue,
        string $datePassage,
        DonneurDOrdre $donneurDOrdre,
        AdresseEnlevementV3 $adresseEnlevement,
        ?DestinatairesDpd $destinatairesEsd = null,
        ?string $locale = null,
    ): PickupCreationResult {
        // phpcs:enable
        return $this->pickupService->createEuropeanPickup(
            $this->accountNumber,
            $this->password,
            $headerValue,
            $datePassage,
            $donneurDOrdre,
            $adresseEnlevement,
            $destinatairesEsd,
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
            $this->accountNumber,
            $this->password,
            $esdNumbers,
            $locale,
        );
    }

    /**
     * Calculate possible products for a shipment (V2, with caller token).
     *
     * @param  string                                                   $caller       The caller token.
     * @param  \Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode      $from         The sender's postal code.
     * @param  \Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode      $to           The recipient's postal code.
     * @param  string                                                   $toCityName   The recipient's city name.
     * @param  \Kwaadpepper\ChronopostApiPhp\Enums\ShippingType         $shippingType The shipping type.
     * @param  float                                                    $weight       The weight in kilograms.
     * @param  float|null                                               $height       The height in centimeters.
     * @param  float|null                                               $length       The length in centimeters.
     * @param  float|null                                               $width        The width in centimeters.
     * @param  \DateTime|null                                           $shippingDate The desired shipping date.
     * @param  string|null                                              $nationalite  The nationality code.
     * @param  string|null                                              $isPart       Whether the sender is a private individual.
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\ProductList  The list of possible products.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the API call fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Calculate\CalculateException If the API returns an error.
     */
    public function calculatePossibleProductsForShippingV2(
        string $caller,
        PostCode $from,
        PostCode $to,
        string $toCityName,
        ShippingType $shippingType,
        float $weight,
        ?float $height = null,
        ?float $length = null,
        ?float $width = null,
        ?DateTime $shippingDate = null,
        ?string $nationalite = null,
        ?string $isPart = null,
    ): ProductList {
        return $this->calculateService->calculateProductsV2(
            $caller,
            $from,
            $to,
            $toCityName,
            $shippingType,
            $weight,
            $height,
            $length,
            $width,
            $shippingDate,
            $nationalite,
            $isPart,
        );
    }

    /**
     * Get available products for a route (without pricing).
     *
     * @param  \Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode         $from         The sender's postal code.
     * @param  \Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode         $to           The recipient's postal code.
     * @param  string                                                      $toCityName   The recipient's city name.
     * @param  \Kwaadpepper\ChronopostApiPhp\Enums\ShippingType            $shippingType The shipping type.
     * @param  float                                                       $weight       The weight in kilograms.
     * @param  float|null                                                  $height       The height in centimeters.
     * @param  float|null                                                  $length       The length in centimeters.
     * @param  float|null                                                  $width        The width in centimeters.
     * @param  \DateTime|null                                              $shippingDate The desired shipping date.
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\ProductCatalog  The available products catalog.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the API call fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\QuickCost\QuickCostException If the API returns an error.
     */
    public function getAvailableProducts(
        PostCode $from,
        PostCode $to,
        string $toCityName,
        ShippingType $shippingType,
        float $weight,
        ?float $height = null,
        ?float $length = null,
        ?float $width = null,
        ?DateTime $shippingDate = null,
    ): ProductCatalog {
        return $this->quickCostService->getProducts(
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
            $shippingDate,
        );
    }

    /**
     * Search for available delivery time slots.
     *
     * @param string      $productType     The product type code.
     * @param string      $recipientAddr1  Recipient address line 1.
     * @param string      $recipientZip    Recipient postal code.
     * @param string      $recipientCity   Recipient city.
     * @param string      $recipientCountry Recipient country code.
     * @param string      $dateBegin       Start date (YYYY-MM-DD).
     * @param string      $dateEnd         End date (YYYY-MM-DD).
     * @param string|null $shipperAddr1    Shipper address line 1.
     * @param string|null $shipperZip      Shipper postal code.
     * @param string|null $shipperCity     Shipper city.
     * @param string|null $shipperCountry  Shipper country code.
     * @param integer|null $weight         Weight in grams.
     * @param string|null $slotType        Slot type filter.
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot\DeliverySlotSearchResult
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\DeliverySlot\DeliverySlotException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     */
    public function searchDeliverySlots(
        string $productType,
        string $recipientAddr1,
        string $recipientZip,
        string $recipientCity,
        string $recipientCountry,
        string $dateBegin,
        string $dateEnd,
        ?string $shipperAddr1 = null,
        ?string $shipperZip = null,
        ?string $shipperCity = null,
        ?string $shipperCountry = null,
        ?int $weight = null,
        ?string $slotType = null,
    ): DeliverySlotSearchResult {
        return $this->deliverySlotService->searchDeliverySlots(
            $this->accountNumber,
            $this->password,
            $productType,
            $recipientAddr1,
            $recipientZip,
            $recipientCity,
            $recipientCountry,
            $dateBegin,
            $dateEnd,
            $shipperAddr1,
            $shipperZip,
            $shipperCity,
            $shipperCountry,
            $weight,
            $slotType,
        );
    }

    /**
     * Confirm a delivery time slot.
     *
     * @param string $productType   The product type code.
     * @param string $codeSlot      The delivery slot code.
     * @param string $meshCode      The mesh code from search result.
     * @param string $transactionId The transaction ID from search result.
     * @param string $rank          The rank of the slot.
     * @param string $position      The position of the slot.
     * @param string $dateSelected  The selected date.
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot\DeliverySlotConfirmation
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\DeliverySlot\DeliverySlotException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     */
    public function confirmDeliverySlot(
        string $productType,
        string $codeSlot,
        string $meshCode,
        string $transactionId,
        string $rank,
        string $position,
        string $dateSelected,
    ): DeliverySlotConfirmation {
        return $this->deliverySlotService->confirmDeliverySlot(
            $this->accountNumber,
            $this->password,
            $productType,
            $codeSlot,
            $meshCode,
            $transactionId,
            $rank,
            $position,
            $dateSelected,
        );
    }

    /**
     * Geocode an address to get coordinates.
     *
     * @param string      $address1 Address line 1.
     * @param string      $zipCode  Postal code.
     * @param string      $city     City name.
     * @param string|null $address2 Address line 2.
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot\GeocodingResult
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\DeliverySlot\DeliverySlotException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     */
    public function geocodeAddress(
        string $address1,
        string $zipCode,
        string $city,
        ?string $address2 = null,
    ): GeocodingResult {
        return $this->deliverySlotService->geocodeAddress(
            $this->accountNumber,
            $this->password,
            $address1,
            $zipCode,
            $city,
            $address2,
        );
    }
}
