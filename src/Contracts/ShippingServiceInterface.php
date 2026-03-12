<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Contracts;

use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\MonoParcelV7;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\MultiParcelV4;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\ReservationMultiParcelResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\ReservationResult;
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

interface ShippingServiceInterface
{
    /**
     * @param AccountNumber          $accountNumber
     * @param Password               $password
     * @param SkyBillValue           $skybillValue
     * @param CustomerValue          $customerValue
     * @param ShipperValue           $shipperValue
     * @param RecipientValue         $recipientValue
     * @param ReferenceValue         $referenceValue
     * @param ScheduledValue|null    $scheduledValue
     * @param EsdValue|null          $esdValue
     * @param SkyBillOutputMode      $skyBillOutputMode
     * @param SkyBillParameters|null $skyBillParameters
     * @return MultiParcelV4
     */
    public function singleParcelV4(
        AccountNumber $accountNumber,
        Password $password,
        SkyBillValue $skybillValue,
        CustomerValue $customerValue,
        ShipperValue $shipperValue,
        RecipientValue $recipientValue,
        ReferenceValue $referenceValue,
        ?ScheduledValue $scheduledValue = null,
        ?EsdValue $esdValue = null,
        SkyBillOutputMode $skyBillOutputMode = SkyBillOutputMode::NO_MAIL_SENDING,
        ?SkyBillParameters $skyBillParameters = null,
    ): MultiParcelV4;

    /**
     * @param AccountNumber              $accountNumber
     * @param Password                   $password
     * @param array<int, SkyBillValue>   $skybillValues
     * @param CustomerValue              $customerValue
     * @param array<int, ShipperValue>   $shippersValues
     * @param array<int, RecipientValue> $recipientsValues
     * @param array<int, ReferenceValue> $referenceValues
     * @param array<int, ScheduledValue> $scheduledValues
     * @param EsdValue|null              $esdValue
     * @param integer                    $numberOfParcel
     * @param boolean                    $multiParcel
     * @param SkyBillOutputMode          $skyBillOutputMode
     * @param SkyBillParameters|null     $skyBillParameters
     * @return MultiParcelV4
     */
    public function multiParcelV4(
        AccountNumber $accountNumber,
        Password $password,
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
    ): MultiParcelV4;

    /**
     * @param AccountNumber          $accountNumber
     * @param Password               $password
     * @param SkyBillValue           $skybillValue
     * @param CustomerValue          $customerValue
     * @param ShipperValue           $shipperValue
     * @param RecipientValue         $recipientValue
     * @param ReferenceValue         $referenceValue
     * @param ScheduledValue|null    $scheduledValue
     * @param EsdValue|null          $esdValue
     * @param SkyBillOutputMode      $skyBillOutputMode
     * @param SkyBillParameters|null $skyBillParameters
     * @return MonoParcelV7
     */
    public function singleParcelV7(
        AccountNumber $accountNumber,
        Password $password,
        SkyBillValue $skybillValue,
        CustomerValue $customerValue,
        ShipperValue $shipperValue,
        RecipientValue $recipientValue,
        ReferenceValue $referenceValue,
        ?ScheduledValue $scheduledValue = null,
        ?EsdValue $esdValue = null,
        SkyBillOutputMode $skyBillOutputMode = SkyBillOutputMode::NO_MAIL_SENDING,
        ?SkyBillParameters $skyBillParameters = null,
    ): MonoParcelV7;

    /**
     * @param AccountNumber              $accountNumber
     * @param Password                   $password
     * @param array<int, SkyBillValue>   $skybillValues
     * @param CustomerValue              $customerValue
     * @param array<int, ShipperValue>   $shippersValues
     * @param array<int, RecipientValue> $recipientsValues
     * @param array<int, ReferenceValue> $referenceValues
     * @param array<int, ScheduledValue> $scheduledValues
     * @param EsdValue|null              $esdValue
     * @param integer                    $numberOfParcel
     * @param boolean                    $multiParcel
     * @param SkyBillOutputMode          $skyBillOutputMode
     * @param SkyBillParameters|null     $skyBillParameters
     * @return MultiParcelV4
     */
    public function multiParcelV7(
        AccountNumber $accountNumber,
        Password $password,
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
    ): MultiParcelV4;

    /**
     * @param AccountNumber          $accountNumber
     * @param Password               $password
     * @param SkyBillValue           $skybillValue
     * @param CustomerValue          $customerValue
     * @param ShipperValue           $shipperValue
     * @param RecipientValue         $recipientValue
     * @param ReferenceValue         $referenceValue
     * @param EsdValue|null          $esdValue
     * @param ScheduledValue|null    $scheduledValue
     * @param SkyBillOutputMode      $skyBillOutputMode
     * @param SkyBillParameters|null $skyBillParameters
     * @return ReservationResult
     */
    public function singleParcelWithReservation(
        AccountNumber $accountNumber,
        Password $password,
        SkyBillValue $skybillValue,
        CustomerValue $customerValue,
        ShipperValue $shipperValue,
        RecipientValue $recipientValue,
        ReferenceValue $referenceValue,
        ?EsdValue $esdValue = null,
        ?ScheduledValue $scheduledValue = null,
        SkyBillOutputMode $skyBillOutputMode = SkyBillOutputMode::NO_MAIL_SENDING,
        ?SkyBillParameters $skyBillParameters = null,
    ): ReservationResult;

    /**
     * @param AccountNumber              $accountNumber
     * @param Password                   $password
     * @param array<int, SkyBillValue>   $skybillValues
     * @param CustomerValue              $customerValue
     * @param ShipperValue               $shipperValue
     * @param array<int, RecipientValue> $recipientsValues
     * @param array<int, ReferenceValue> $referenceValues
     * @param EsdValue|null              $esdValue
     * @param ScheduledValue|null        $scheduledValue
     * @param integer                    $numberOfParcel
     * @param boolean                    $multiParcel
     * @param SkyBillOutputMode          $skyBillOutputMode
     * @param SkyBillParameters|null     $skyBillParameters
     * @return ReservationMultiParcelResult
     */
    public function multiParcelWithReservation(
        AccountNumber $accountNumber,
        Password $password,
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
    ): ReservationMultiParcelResult;

    /**
     * @param AccountNumber          $accountNumber
     * @param Password               $password
     * @param SkyBillValue           $skybillValue
     * @param CustomerValue          $customerValue
     * @param ShipperValue           $shipperValue
     * @param RecipientValue         $recipientValue
     * @param ReferenceValue         $referenceValue
     * @param EsdValue               $esdValue
     * @param SkyBillOutputMode      $skyBillOutputMode
     * @param SkyBillParameters|null $skyBillParameters
     * @return ReservationResult
     */
    public function shippingWithEsdOnly(
        AccountNumber $accountNumber,
        Password $password,
        SkyBillValue $skybillValue,
        CustomerValue $customerValue,
        ShipperValue $shipperValue,
        RecipientValue $recipientValue,
        ReferenceValue $referenceValue,
        EsdValue $esdValue,
        SkyBillOutputMode $skyBillOutputMode = SkyBillOutputMode::NO_MAIL_SENDING,
        ?SkyBillParameters $skyBillParameters = null,
    ): ReservationResult;

    /**
     * @param AccountNumber     $accountNumber
     * @param Password          $password
     * @param SkyBillValue      $skybillValue
     * @param CustomerValue     $customerValue
     * @param ShipperValue      $shipperValue
     * @param RecipientValue    $recipientValue
     * @param ReferenceValue    $referenceValue
     * @param EsdValue          $esdValue
     * @param SkyBillOutputMode $skyBillOutputMode
     * @return ReservationResult
     */
    public function shippingWithReservationAndEsd(
        AccountNumber $accountNumber,
        Password $password,
        SkyBillValue $skybillValue,
        CustomerValue $customerValue,
        ShipperValue $shipperValue,
        RecipientValue $recipientValue,
        ReferenceValue $referenceValue,
        EsdValue $esdValue,
        SkyBillOutputMode $skyBillOutputMode = SkyBillOutputMode::NO_MAIL_SENDING,
    ): ReservationResult;
}
