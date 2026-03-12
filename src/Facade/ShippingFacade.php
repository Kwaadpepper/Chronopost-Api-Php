<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Facade;

use ChronopostShipping\StructType\RecipientValue as RecipientValueChronopost;
use ChronopostShipping\StructType\ShipperValue as ShipperValueChronopost;
use ChronopostShipping\StructType\SkybillValueBase;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\MonoParcelV7;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\MultiParcelV4;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\ReservationMultiParcelResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\ReservationResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\RoutingInfo;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\ShippingInformation;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\SkybillLabel;
use Kwaadpepper\ChronopostApiPhp\Enums\SkyBillOutputMode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\CustomerValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\EsdValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\RecipientValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ReferenceValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ScheduledValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ShipperValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\SkyBillParameters;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\SkyBillValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\RoutingQuery;
use Kwaadpepper\ChronopostApiPhp\Services\Shipping\ShippingLabelService;
use Kwaadpepper\ChronopostApiPhp\Services\Shipping\ShippingService;

class ShippingFacade
{
    public function __construct(
        private ShippingService $shippingService,
        private ShippingLabelService $shippingLabelService,
    ) {
    }

    /**
     * Creates a single-parcel shipment with the provided values.
     *
     * @throws \InvalidArgumentException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\EsdException
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
     * Creates a multi-parcel shipment (one shipper to one recipient).
     *
     * @param  \Kwaadpepper\ChronopostApiPhp\ObjectValues\MultiParcelPart[] $multiParcelParts
     *
     * @throws \InvalidArgumentException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\EsdException
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
     * Creates a single-parcel shipment using the V7 API.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\EsdException
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
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\EsdException
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
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\EsdException
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
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\EsdException
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
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\EsdException
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
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\EsdException
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
        return $this->shippingLabelService->getSkybill($numberSearch, $mode, $key);
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
    public function getRouting(RoutingQuery $query): RoutingInfo
    {
        return $this->shippingLabelService->getRouting($query);
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
}
