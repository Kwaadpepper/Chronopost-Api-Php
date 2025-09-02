<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp;

use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\QuickCostV3;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\MultiParcelV4;
use Kwaadpepper\ChronopostApiPhp\Enums\ShippingType;
use Kwaadpepper\ChronopostApiPhp\Enums\SkyBillOutputMode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
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
use Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingNumber;
use Kwaadpepper\ChronopostApiPhp\Services\Cost\QuickCostService;
use Kwaadpepper\ChronopostApiPhp\Services\Shipping\ShippingService;
use Kwaadpepper\ChronopostApiPhp\Services\Tracking\TrackSearchService;
use WsdlToPhp\PackageBase\SoapClientInterface;

class ChronopostApi
{
    private TrackSearchService $trackSearchService;

    private ShippingService $shippingService;

    private QuickCostService $quickCostService;


    /**
     * Constructor
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber $accountNumber The account number.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Password      $password      The password.
     */
    public function __construct(
        #[\SensitiveParameter] private AccountNumber $accountNumber,
        #[\SensitiveParameter] private Password $password
    ) {
        $defaultSopapOptions = [
            SoapClientInterface::WSDL_LOGIN => $accountNumber->getAccountNumber(),
            SoapClientInterface::WSDL_PASSWORD => $password->getPassword(),
        ];

        $this->trackSearchService = new TrackSearchService($defaultSopapOptions);
        $this->shippingService    = new ShippingService($defaultSopapOptions);
        $this->quickCostService   = new QuickCostService($defaultSopapOptions);
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

    /**
     * Estimate the shipping cost for a shipment.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode    $from          The sender's postal code.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode    $to            The recipient's postal code.
     * @param integer                                                $weightInGrams The weight of the shipment in grams.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\ProductCode $productCode   The product code for the shipment.
     * @param \Kwaadpepper\ChronopostApiPhp\Enums\ShippingType       $shippingType  The shipping type.
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\QuickCostV3 The estimated shipping cost.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError                     If the API call fails.
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
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\SkyBillValue           $skybillValue
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\CustomerValue          $customerValue
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ShipperValue           $shipperValue
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\RecipientValue         $recipientValue
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ReferenceValue         $referenceValue
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ScheduledValue|null    $scheduledValue
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\EsdValue|null          $esdValue
     * @param \Kwaadpepper\ChronopostApiPhp\Enums\SkyBillOutputMode                    $skyBillOutputMode
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\SkyBillParameters|null $skyBillParameters
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\MultiParcelV4
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
         * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\MultiParcelPart[]             $multiParcelParts
         * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\CustomerValue          $customerValue
         * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ShipperValue           $shipperValue
         * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\RecipientValue         $recipientValue
         * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\EsdValue|null          $esdValue
         * @param \Kwaadpepper\ChronopostApiPhp\Enums\SkyBillOutputMode                    $skyBillOutputMode
         * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\SkyBillParameters|null $skyBillParameters
         *
         * @return \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\MultiParcelV4
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
}
