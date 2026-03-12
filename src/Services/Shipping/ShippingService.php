<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Services\Shipping;

use ChronopostShipping\ClassMap;
use ChronopostShipping\ServiceType\Shipping;
use ChronopostShipping\StructType\AppointmentValue as AppointmentValueChronopost;
use ChronopostShipping\StructType\CustomerValue as CustomerValueChronopost;
use ChronopostShipping\StructType\EsdValue as EsdValueChronopost;
use ChronopostShipping\StructType\EsdValue3;
use ChronopostShipping\StructType\EsdWithRefClientValue;
use ChronopostShipping\StructType\EsdWithRefClientValueV2;
use ChronopostShipping\StructType\HeaderValue;
use ChronopostShipping\StructType\HeaderValueV2;
use ChronopostShipping\StructType\RecipientValue as RecipientValueChronopost;
use ChronopostShipping\StructType\RecipientValueV2;
use ChronopostShipping\StructType\RefValue;
use ChronopostShipping\StructType\RefValueV2;
use ChronopostShipping\StructType\ScheduledValue as ScheduledValueChronopost;
use ChronopostShipping\StructType\ShipperValue as ShipperValueChronopost;
use ChronopostShipping\StructType\ShipperValueV2;
use ChronopostShipping\StructType\ShippingMultiParcelV4;
use ChronopostShipping\StructType\ShippingMultiParcelV7;
use ChronopostShipping\StructType\ShippingMultiParcelWithReservationV3;
use ChronopostShipping\StructType\ShippingV7 as ShippingV7Struct;
use ChronopostShipping\StructType\ShippingWithESDOnlyV2;
use ChronopostShipping\StructType\ShippingWithReservationAndESDWithRefClientPC;
use ChronopostShipping\StructType\ShippingWithReservationV2;
use ChronopostShipping\StructType\SkybillParamsValue;
use ChronopostShipping\StructType\SkybillParamsValueV2;
use ChronopostShipping\StructType\SkybillValue as SkybillValueChronopost;
use ChronopostShipping\StructType\SkybillValueV2 as SkybillValueV2Chronopost;
use ChronopostShipping\StructType\SkybillWithDimensionsValueV2 as SkybillDimV2;
use ChronopostShipping\StructType\SkybillWithDimensionsValueV3 as SkybillDimV3;
use ChronopostShipping\StructType\SkybillWithDimensionsValueV6;
use ChronopostShipping\StructType\SkybillWithDimensionsValueV8 as SkybillDimV8;
use Kwaadpepper\ChronopostApiPhp\Contracts\ShippingServiceInterface;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\MonoParcelV7;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\MultiParcelV4;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\ReservationMultiParcelResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\ReservationResult;
use Kwaadpepper\ChronopostApiPhp\Enums\SkyBillOutputMode;
use Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError;
use Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\EsdException;
use Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException;
use Kwaadpepper\ChronopostApiPhp\Factory\MonoParcelV7Factory;
use Kwaadpepper\ChronopostApiPhp\Factory\MultiParcelV4Factory;
use Kwaadpepper\ChronopostApiPhp\Factory\ReservationMultiParcelResultFactory;
use Kwaadpepper\ChronopostApiPhp\Factory\ReservationResultFactory;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\AppointementValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\CustomerValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\EsdValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\RecipientValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ReferenceValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ScheduledValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ShipperValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\SkyBillParameters;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\SkyBillValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;
use WsdlToPhp\PackageBase\SoapClientInterface;

class ShippingService implements ShippingServiceInterface
{
    /**
     * Soap tracking service
     *
     * @var \ChronopostShipping\ServiceType\Shipping
     */
    private Shipping $shippingService;

    /**
     * Tracking service soap url
     *
     * @var string
     */
    protected static string $serviceUrl = 'https://ws.chronopost.fr/shipping-cxf/ShippingServiceWS?wsdl';

    /**
     * Constructor
     *
     * @param array<string, mixed>                          $soapOptions     Additional options for the soap client.
     * @param \ChronopostShipping\ServiceType\Shipping|null $shippingService Injected shipping service for testing.
     */
    public function __construct(
        #[\SensitiveParameter] private AccountNumber $accountNumber,
        #[\SensitiveParameter] private Password $password,
        array $soapOptions = [],
        ?Shipping $shippingService = null,
    ) {
        if ($shippingService !== null) {
            $this->shippingService = $shippingService;
            return;
        }

        $soapOptions = array_merge(
            $soapOptions,
            [
                SoapClientInterface::WSDL_URL => static::$serviceUrl,
                SoapClientInterface::WSDL_CLASSMAP => ClassMap::get(),
            ],
        );

        $this->shippingService = new Shipping($soapOptions);
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
        ?SkyBillParameters $skyBillParameters = null,
    ): MultiParcelV4 {
        return $this->multiParcelV4(
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
     * Creates a multi-parcel shipment with the provided values.
     *
     * @param array<int, SkyBillValue>                                                 $skybillValues
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\CustomerValue          $customerValue
     * @param array<int, ShipperValue>                                                 $shippersValues
     * @param array<int, RecipientValue>                                               $recipientsValues
     * @param array<int, ReferenceValue>                                               $referenceValues
     * @param array<int, ScheduledValue>                                               $scheduledValues
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\EsdValue|null          $esdValue
     * @param integer                                                                  $numberOfParcel
     * @param boolean                                                                  $multiParcel
     * @param \Kwaadpepper\ChronopostApiPhp\Enums\SkyBillOutputMode                    $skyBillOutputMode
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\SkyBillParameters|null $skyBillParameters
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\MultiParcelV4
     *
     * @throws \InvalidArgumentException If the provided values are invalid.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the API call fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException If the shipping operation fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\EsdException If the ESD operation fails.
     * @phpcs:disable Generic.Metrics.CyclomaticComplexity.TooHigh
     */
    public function multiParcelV4(
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
        // phpcs:enable

        if ($multiParcel && count($recipientsValues) > 1) {
            throw new \InvalidArgumentException(
                'When using multi-parcel, you cannot provide more than one recipient.',
            );
        }

        if (count($shippersValues) > $numberOfParcel) {
            throw new \InvalidArgumentException(
                'Too many shippers values provided. It must not exceed the number of parcels.',
            );
        }

        if (count($recipientsValues) > $numberOfParcel) {
            throw new \InvalidArgumentException(
                'Too many recipients values provided. It must not exceed the number of parcels.',
            );
        }

        if (count($referenceValues) !== $numberOfParcel) {
            throw new \InvalidArgumentException(
                'The number of reference values must match the number of parcels.',
            );
        }

        if (count($skybillValues) !== $numberOfParcel) {
            throw new \InvalidArgumentException(
                'The number of skybill values must match the number of parcels.',
            );
        }

        if (count($scheduledValues) > $numberOfParcel) {
            throw new \InvalidArgumentException(
                'Too many scheduled values provided. It must not exceed the number of parcels.',
            );
        }

        $headerValue = new HeaderValue(
            (int) ($this->accountNumber->getAccountNumber()),
            'CHRFR',
            '',
        );

        $skyBillParameters = $skyBillParameters ?? new SkyBillParameters();

        $parameters = new ShippingMultiParcelV4(
            skybillParamsValue: $this->mapParameters($skyBillParameters),
            password: $this->password->getPassword(),
            version: '2.0',
            numberOfParcel: $numberOfParcel,
            multiParcel: $multiParcel ? 'Y' : 'N',
            modeRetour: (string) ($skyBillOutputMode->value),
            headerValue: $headerValue,
            esdValue: $esdValue ? $this->mapEsdValue($esdValue) : null,
            skybillValue: array_map(
                fn (SkyBillValue $skybillValue) =>
                $this->mapSkybillValue($skybillValue),
                $skybillValues,
            ),
            customerValue: $this->mapCustomerValue($customerValue),
            refValue: array_map(
                fn (ReferenceValue $referenceValue) =>
                $this->mapReferenceValue($referenceValue),
                $referenceValues,
            ),
            shipperValue: array_map(
                fn (ShipperValue $shipperValue) =>
                $this->mapShipperValue($shipperValue),
                $shippersValues,
            ),
            recipientValue: array_map(
                fn (RecipientValue $recipientValue) =>
                $this->mapRecipientValue($recipientValue),
                $recipientsValues,
            ),
            scheduledValue: array_map(
                fn (ScheduledValue $scheduledValue) =>
                $this->mapScheduledValue($scheduledValue),
                $scheduledValues,
            ),
        );


        $result = $this->shippingService->shippingMultiParcelV4($parameters);
        if ($result === false) {
            $lastError = $this->shippingService->getLastErrorForMethod(methodName: Shipping::class . '::shippingMultiParcelV4');
            throw new ApiError('Failed to call from shipping service', $lastError);
        }

        $response = $result->getReturn();
        if ($response === null) {
            throw new ApiError('Failed to get result from shippingMultiParcelV4 service, null response');
        }

        if ($response->getErrorCode() !== 0) {
            $errorMessage = $response->getErrorMessage();
            $errorCode    = $response->getErrorCode();

            if ($esdValue !== null) {
                EsdException::throwIfEsdError(
                    $errorCode,
                    $errorMessage,
                );
            }

            throw new ShippingException($errorMessage, $errorCode);
        }

        $factory = new MultiParcelV4Factory();

        return $factory->create($response);
    }

    /**
     * Creates a single-parcel V7 shipment.
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
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\MonoParcelV7
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the API call fails or returns an invalid response.
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
        $headerValue = new HeaderValue(
            (int) ($this->accountNumber->getAccountNumber()),
            'CHRFR',
            '',
        );

        $skyBillParameters = $skyBillParameters ?? new SkyBillParameters();

        $parameters = new ShippingV7Struct(
            esdValue: $esdValue ? $this->mapEsdValueV1($esdValue) : null,
            headerValue: $headerValue,
            shipperValue: $this->mapShipperValueV1($shipperValue),
            customerValue: $this->mapCustomerValue($customerValue),
            recipientValue: $this->mapRecipientValueV1($recipientValue),
            refValue: $this->mapReferenceValueV1($referenceValue),
            skybillValue: $this->mapSkybillValueV3($skybillValue),
            skybillParamsValue: $this->mapParameters($skyBillParameters),
            password: $this->password->getPassword(),
            modeRetour: (string) ($skyBillOutputMode->value),
            version: '2.0',
            scheduledValue: $scheduledValue ? $this->mapScheduledValue($scheduledValue) : null,
        );

        $result = $this->shippingService->shippingV7($parameters);
        if ($result === false) {
            $lastError = $this->shippingService->getLastErrorForMethod(
                methodName: Shipping::class . '::shippingV7',
            );
            throw new ApiError('Failed to call from shipping service', $lastError);
        }

        $response = $result->getReturn();
        if ($response === null) {
            throw new ApiError('Failed to get result from shippingV7 service, null response');
        }

        if ($response->getErrorCode() !== 0) {
            $errorMessage = $response->getErrorMessage();
            $errorCode    = $response->getErrorCode();

            if ($esdValue !== null) {
                EsdException::throwIfEsdError($errorCode, $errorMessage);
            }

            throw new ShippingException($errorMessage, $errorCode);
        }

        return (new MonoParcelV7Factory())->create($response);
    }

    /**
     * Creates a multi-parcel V7 shipment.
     *
     * @param array<int, SkyBillValue>                                                 $skybillValues
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\CustomerValue          $customerValue
     * @param array<int, ShipperValue>                                                 $shippersValues
     * @param array<int, RecipientValue>                                               $recipientsValues
     * @param array<int, ReferenceValue>                                               $referenceValues
     * @param array<int, ScheduledValue>                                               $scheduledValues
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\EsdValue|null          $esdValue
     * @param integer                                                                  $numberOfParcel
     * @param boolean                                                                  $multiParcel
     * @param \Kwaadpepper\ChronopostApiPhp\Enums\SkyBillOutputMode                    $skyBillOutputMode
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\SkyBillParameters|null $skyBillParameters
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\MultiParcelV4
     *
     * @throws \InvalidArgumentException If the provided values are invalid.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the API call fails or returns an invalid response.
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
        // phpcs:enable
        $headerValue = new HeaderValueV2();
        $headerValue->setAccountNumber((int) ($this->accountNumber->getAccountNumber()));
        $headerValue->setIdEmit('CHRFR');
        $headerValue->setIdentWebPro('');

        $skyBillParameters = $skyBillParameters ?? new SkyBillParameters();

        $parameters = new ShippingMultiParcelV7(
            esdValue: $esdValue ? $this->mapEsdValue($esdValue) : null,
            headerValue: $headerValue,
            shipperValue: array_map(
                fn (ShipperValue $sv) => $this->mapShipperValue($sv),
                $shippersValues,
            ),
            customerValue: $this->mapCustomerValue($customerValue),
            recipientValue: array_map(
                fn (RecipientValue $rv) => $this->mapRecipientValue($rv),
                $recipientsValues,
            ),
            refValue: array_map(
                fn (ReferenceValue $rv) => $this->mapReferenceValue($rv),
                $referenceValues,
            ),
            skybillValue: array_map(
                fn (SkyBillValue $sv) => $this->mapSkybillValueV8($sv),
                $skybillValues,
            ),
            skybillParamsValue: $this->mapParameters($skyBillParameters),
            password: $this->password->getPassword(),
            modeRetour: (string) ($skyBillOutputMode->value),
            numberOfParcel: $numberOfParcel,
            version: '2.0',
            multiParcel: $multiParcel ? 'Y' : 'N',
            scheduledValue: array_map(
                fn (ScheduledValue $sv) => $this->mapScheduledValue($sv),
                $scheduledValues,
            ),
        );

        $result = $this->shippingService->shippingMultiParcelV7($parameters);
        if ($result === false) {
            $lastError = $this->shippingService->getLastErrorForMethod(
                methodName: Shipping::class . '::shippingMultiParcelV7',
            );
            throw new ApiError('Failed to call from shipping service', $lastError);
        }

        $response = $result->getReturn();
        if ($response === null) {
            throw new ApiError('Failed to get result from shippingMultiParcelV7 service, null response');
        }

        if ($response->getErrorCode() !== 0) {
            $errorMessage = $response->getErrorMessage();
            $errorCode    = $response->getErrorCode();

            if ($esdValue !== null) {
                EsdException::throwIfEsdError($errorCode, $errorMessage);
            }

            throw new ShippingException($errorMessage, $errorCode);
        }

        return (new MultiParcelV4Factory())->create($response);
    }

    /**
     * Creates a single-parcel shipment with reservation.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\SkyBillValue           $skybillValue
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\CustomerValue          $customerValue
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ShipperValue           $shipperValue
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\RecipientValue         $recipientValue
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ReferenceValue         $referenceValue
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\EsdValue|null          $esdValue
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ScheduledValue|null    $scheduledValue
     * @param \Kwaadpepper\ChronopostApiPhp\Enums\SkyBillOutputMode                    $skyBillOutputMode
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\SkyBillParameters|null $skyBillParameters
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\ReservationResult
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the API call fails or returns an invalid response.
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
        $headerValue = new HeaderValue(
            (int) ($this->accountNumber->getAccountNumber()),
            'CHRFR',
            '',
        );

        $skyBillParameters = $skyBillParameters ?? new SkyBillParameters();

        $parameters = new ShippingWithReservationV2(
            esdValue: $esdValue ? $this->mapEsdWithRefClient($esdValue) : null,
            headerValue: $headerValue,
            shipperValue: $this->mapShipperValueV1($shipperValue),
            customerValue: $this->mapCustomerValue($customerValue),
            recipientValue: $this->mapRecipientValueV1($recipientValue),
            refValue: $this->mapReferenceValueV1($referenceValue),
            skybillValue: $this->mapSkybillValueV2($skybillValue),
            skybillParamsValue: $this->mapParametersV1($skyBillParameters),
            password: $this->password->getPassword(),
            modeRetour: (string) ($skyBillOutputMode->value),
            version: '2.0',
            scheduledValue: $scheduledValue ? $this->mapScheduledValue($scheduledValue) : null,
        );

        $result = $this->shippingService->shippingWithReservationV2($parameters);
        if ($result === false) {
            $lastError = $this->shippingService->getLastErrorForMethod(
                methodName: Shipping::class . '::shippingWithReservationV2',
            );
            throw new ApiError('Failed to call from shipping service', $lastError);
        }

        $response = $result->getReturn();
        if ($response === null) {
            throw new ApiError('Failed to get result from shippingWithReservationV2 service, null response');
        }

        if ($response->getErrorCode() !== 0) {
            $errorMessage = $response->getErrorMessage();
            $errorCode    = $response->getErrorCode();

            if ($esdValue !== null) {
                EsdException::throwIfEsdError($errorCode, $errorMessage);
            }

            throw new ShippingException($errorMessage, $errorCode);
        }

        return (new ReservationResultFactory())->create($response);
    }

    /**
     * Creates a multi-parcel shipment with reservation.
     *
     * @param array<int, SkyBillValue>                                                 $skybillValues
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\CustomerValue          $customerValue
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ShipperValue           $shipperValue
     * @param array<int, RecipientValue>                                               $recipientsValues
     * @param array<int, ReferenceValue>                                               $referenceValues
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\EsdValue|null          $esdValue
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ScheduledValue|null    $scheduledValue
     * @param integer                                                                  $numberOfParcel
     * @param boolean                                                                  $multiParcel
     * @param \Kwaadpepper\ChronopostApiPhp\Enums\SkyBillOutputMode                    $skyBillOutputMode
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\SkyBillParameters|null $skyBillParameters
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\ReservationMultiParcelResult
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the API call fails or returns an invalid response.
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
        $headerValue = new HeaderValue(
            (int) ($this->accountNumber->getAccountNumber()),
            'CHRFR',
            '',
        );

        $skyBillParameters = $skyBillParameters ?? new SkyBillParameters();

        $parameters = new ShippingMultiParcelWithReservationV3(
            esdValue: $esdValue ? $this->mapEsdValue($esdValue) : null,
            headerValue: $headerValue,
            shipperValue: $this->mapShipperValueV1($shipperValue),
            customerValue: $this->mapCustomerValue($customerValue),
            recipientValue: array_map(
                fn (RecipientValue $rv) => $this->mapRecipientValueV1($rv),
                $recipientsValues,
            ),
            refValue: array_map(
                fn (ReferenceValue $rv) => $this->mapReferenceValueV1($rv),
                $referenceValues,
            ),
            skybillValue: array_map(
                fn (SkyBillValue $sv) => $this->mapSkybillValueDimV2($sv),
                $skybillValues,
            ),
            skybillParamsValue: $this->mapParametersV1($skyBillParameters),
            password: $this->password->getPassword(),
            modeRetour: (string) ($skyBillOutputMode->value),
            numberOfParcel: $numberOfParcel,
            version: '2.0',
            multiParcel: $multiParcel ? 'Y' : 'N',
            scheduledValue: $scheduledValue ? $this->mapScheduledValue($scheduledValue) : null,
        );

        $result = $this->shippingService->shippingMultiParcelWithReservationV3($parameters);
        if ($result === false) {
            $lastError = $this->shippingService->getLastErrorForMethod(
                methodName: Shipping::class . '::shippingMultiParcelWithReservationV3',
            );
            throw new ApiError('Failed to call from shipping service', $lastError);
        }

        $response = $result->getReturn();
        if ($response === null) {
            throw new ApiError(
                'Failed to get result from shippingMultiParcelWithReservationV3 service, null response',
            );
        }

        if ($response->getErrorCode() !== 0) {
            $errorMessage = $response->getErrorMessage();
            $errorCode    = $response->getErrorCode();

            if ($esdValue !== null) {
                EsdException::throwIfEsdError($errorCode, $errorMessage);
            }

            throw new ShippingException($errorMessage, $errorCode);
        }

        return (new ReservationMultiParcelResultFactory())->create($response);
    }

    /**
     * Creates a shipment with ESD only (no transport ticket).
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\SkyBillValue           $skybillValue
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\CustomerValue          $customerValue
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ShipperValue           $shipperValue
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\RecipientValue         $recipientValue
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ReferenceValue         $referenceValue
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\EsdValue               $esdValue
     * @param \Kwaadpepper\ChronopostApiPhp\Enums\SkyBillOutputMode                    $skyBillOutputMode
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\SkyBillParameters|null $skyBillParameters
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\ReservationResult
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the API call fails or returns an invalid response.
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
        $headerValue = new HeaderValue(
            (int) ($this->accountNumber->getAccountNumber()),
            'CHRFR',
            '',
        );

        $skyBillParameters = $skyBillParameters ?? new SkyBillParameters();

        $parameters = new ShippingWithESDOnlyV2(
            esdValue: $this->mapEsdWithRefClientV2($esdValue),
            headerValue: $headerValue,
            shipperValue: $this->mapShipperValueV1($shipperValue),
            customerValue: $this->mapCustomerValue($customerValue),
            recipientValue: $this->mapRecipientValueV1($recipientValue),
            refValue: $this->mapReferenceValueV1($referenceValue),
            skybillValue: $this->mapSkybillValueBase($skybillValue),
            skybillParamsValue: $this->mapParametersV1($skyBillParameters),
            password: $this->password->getPassword(),
            modeRetour: (string) ($skyBillOutputMode->value),
            version: '2.0',
        );

        $result = $this->shippingService->shippingWithESDOnlyV2($parameters);
        if ($result === false) {
            $lastError = $this->shippingService->getLastErrorForMethod(
                methodName: Shipping::class . '::shippingWithESDOnlyV2',
            );
            throw new ApiError('Failed to call from shipping service', $lastError);
        }

        $response = $result->getReturn();
        if ($response === null) {
            throw new ApiError('Failed to get result from shippingWithESDOnlyV2 service, null response');
        }

        if ($response->getErrorCode() !== 0) {
            $errorMessage = $response->getErrorMessage();
            $errorCode    = $response->getErrorCode();

            EsdException::throwIfEsdError($errorCode, $errorMessage);

            throw new ShippingException($errorMessage, $errorCode);
        }

        return (new ReservationResultFactory())->create($response);
    }

    /**
     * Creates a shipment with reservation and ESD with client reference.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\SkyBillValue   $skybillValue
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\CustomerValue  $customerValue
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ShipperValue   $shipperValue
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\RecipientValue $recipientValue
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ReferenceValue $referenceValue
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\EsdValue       $esdValue
     * @param \Kwaadpepper\ChronopostApiPhp\Enums\SkyBillOutputMode            $skyBillOutputMode
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\ReservationResult
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the API call fails or returns an invalid response.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException If the shipping operation fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\EsdException If the ESD operation fails.
     * @phpcs:disable Generic.Metrics.CyclomaticComplexity.TooHigh
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
        // phpcs:enable
        $closingDateTime   = $esdValue->closingDateTime;
        $retrievalDateTime = $esdValue->retrievalDateTime;
        $country           = $shipperValue->country;
        $recipientCountry  = $recipientValue->country;
        $shipperPostCode   = $shipperValue->postCode;
        $recipientPostCode = $recipientValue->postCode;
        $shipDateTime      = $skybillValue->shipDateTime;

        $parameters = new ShippingWithReservationAndESDWithRefClientPC();
        $parameters->setRefEsdClient($esdValue->esdClientReference);
        $parameters->setRetrievalDateTime($retrievalDateTime->format('Y-m-d\TH:i:s'));
        $parameters->setClosingDateTime($closingDateTime->format('Y-m-d\TH:i:s'));
        $parameters->setSpecificInstructions($esdValue->specificInstructions);
        $parameters->setShipperCarriesCode($esdValue->shipperCarriesCode);
        $parameters->setShipperBuildingFloor($esdValue->shipperBuildingFloor);
        $parameters->setShipperServiceDirection($esdValue->shipperServiceDirection);
        $parameters->setNombreDePassageMaximum((string) ($esdValue->maximumPasses));
        $parameters->setLtAImprimerParChronopost($esdValue->ltShouldBePrintedByChronopost ? '1' : '0');

        $parameters->setAccountNumber($this->accountNumber->getAccountNumber());
        $parameters->setHeader_idEmit('CHRFR');
        $parameters->setPassword($this->password->getPassword());

        $parameters->setShipperCivility($shipperValue->civility->value);
        $parameters->setShipperName($shipperValue->name);
        $parameters->setShipperName2($shipperValue->name2);
        $parameters->setShipperAdress1($shipperValue->address1);
        $parameters->setShipperAdress2($shipperValue->address2);
        $parameters->setShipperZipCode($shipperPostCode->getPostCode());
        $parameters->setShipperCity($shipperValue->city);
        $parameters->setShipperCountry($country->getCode());
        $parameters->setShipperCountryName($country->getDisplayableName());
        $parameters->setShipperContactName($shipperValue->contactName);
        $parameters->setShipperEmail($shipperValue->email);
        $parameters->setShipperPhone($shipperValue->phone?->getInternationalPhoneNumber());
        $parameters->setShipperMobilePhone($shipperValue->mobilePhone?->getInternationalPhoneNumber());

        $parameters->setCustomerCivility($customerValue->civility->value);
        $parameters->setCustomerName($customerValue->name);
        $parameters->setCustomerName2($customerValue->name2);
        $parameters->setCustomerAdress1($customerValue->address1);
        $parameters->setCustomerAdress2($customerValue->address2);
        $parameters->setCustomerZipCode($customerValue->postCode->getPostCode());
        $parameters->setCustomerCity($customerValue->city);
        $parameters->setCustomerCountry($customerValue->country->getCode());
        $parameters->setCustomerCountryName($customerValue->country->getDisplayableName());
        $parameters->setCustomerContactName($customerValue->contactName);
        $parameters->setCustomerEmail($customerValue->email);
        $parameters->setCustomerPhone($customerValue->phone?->getInternationalPhoneNumber());
        $parameters->setCustomerMobilePhone($customerValue->mobilePhone?->getInternationalPhoneNumber());

        $parameters->setRecipientName($recipientValue->name);
        $parameters->setRecipientName2($recipientValue->name2);
        $parameters->setRecipientAdress1($recipientValue->address1);
        $parameters->setRecipientAdress2($recipientValue->address2);
        $parameters->setRecipientZipCode($recipientPostCode->getPostCode());
        $parameters->setRecipientCity($recipientValue->city);
        $parameters->setRecipientCountry($recipientCountry->getCode());
        $parameters->setRecipientCountryName($recipientCountry->getDisplayableName());
        $parameters->setRecipientContactName($recipientValue->contactName);
        $parameters->setRecipientEmail($recipientValue->email);
        $parameters->setRecipientPhone($recipientValue->phone?->getInternationalPhoneNumber());
        $parameters->setRecipientMobilePhone($recipientValue->mobilePhone?->getInternationalPhoneNumber());

        $parameters->setProductCode($skybillValue->productCode);
        $parameters->setService($skybillValue->serviceCode);
        $parameters->setObjectType($skybillValue->objectType->value);
        $parameters->setWeight((string) (number_format($skybillValue->weight, 2, '.', '')));
        $parameters->setEvtCode('DC');
        $parameters->setModeRetour((string) ($skyBillOutputMode->value));

        if ($shipDateTime !== null) {
            $parameters->setShipDate($shipDateTime->format('Y-m-d\TH:i:s'));
            $parameters->setShipHour((string) ((int) ($shipDateTime->format('H'))));
        }

        $result = $this->shippingService->shippingWithReservationAndESDWithRefClientPC($parameters);
        if ($result === false) {
            $lastError = $this->shippingService->getLastErrorForMethod(
                methodName: Shipping::class . '::shippingWithReservationAndESDWithRefClientPC',
            );
            throw new ApiError('Failed to call from shipping service', $lastError);
        }

        $response = $result->getReturn();
        if ($response === null) {
            throw new ApiError(
                'Failed to get result from shippingWithReservationAndESDWithRefClientPC service, null response',
            );
        }

        if ($response->getErrorCode() !== 0) {
            $errorMessage = $response->getErrorMessage();
            $errorCode    = $response->getErrorCode();

            EsdException::throwIfEsdError($errorCode, $errorMessage);

            throw new ShippingException($errorMessage, $errorCode);
        }

        return (new ReservationResultFactory())->create($response);
    }

    /**
     * Maps a SkyBillValue object to a SkybillWithDimensionsValueV6 object.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\SkyBillValue $skybillValue
     *
     * @return \ChronopostShipping\StructType\SkybillWithDimensionsValueV6
     * @phpcs:disable Generic.Metrics.CyclomaticComplexity.TooHigh
     */
    private function mapSkybillValue(SkyBillValue $skybillValue): SkybillWithDimensionsValueV6
    {
        // phpcs:enable
        $shipDateTime  = $skybillValue->shipDateTime;
        $objectType    = $skybillValue->objectType;
        $parcelContent = $skybillValue->content;
        $skybill       = new SkybillWithDimensionsValueV6();

        if ($parcelContent !== null) {
            $skybill->setContent1($parcelContent->content1);
            if (!empty($parcelContent->content2)) {
                $skybill->setContent2($parcelContent->content2);
            }
            if (!empty($parcelContent->content3)) {
                $skybill->setContent3($parcelContent->content3);
            }
            if (!empty($parcelContent->content4)) {
                $skybill->setContent4($parcelContent->content4);
            }
            if (!empty($parcelContent->content5)) {
                $skybill->setContent5($parcelContent->content5);
            }
        }

        $skybill->setBulkNumber((string) ($skybillValue->bulkNumber));
        $skybill->setCodCurrency($skybillValue->codCurrency);
        $skybill->setCodValue($skybillValue->codValue);
        $skybill->setCustomsCurrency($skybillValue->customsCurrency);
        $skybill->setCustomsValue($skybillValue->customsValue);
        $skybill->setInsuredCurrency($skybillValue->insuredCurrency);
        $skybill->setInsuredValue($skybillValue->insuredValue);

        $skybill->setMasterSkybillNumber($skybillValue->masterSkybillNumber);

        $skybill->setEvtCode('DC');
        $skybill->setObjectType($objectType->value);
        $skybill->setProductCode($skybillValue->productCode);
        $skybill->setService($skybillValue->serviceCode);

        if ($shipDateTime !== null) {
            $skybill->setShipDate($shipDateTime->format('Y-m-d\TH:i:s'));
            $skybill->setShipHour((int) ($shipDateTime->format('H')));
        }

        $skybill->setSkybillRank((string) ($skybillValue->skybillRank));

        $skybill->setWeight((float) (number_format(
            $skybillValue->weight,
            2,
            '.',
            '',
        )));
        $skybill->setWeightUnit('KGM');

        $skybill->setHeight($skybillValue->height);
        $skybill->setLength($skybillValue->length);
        $skybill->setWidth($skybillValue->width);

        if ($skybillValue->as) {
            $skybill->setAs($skybillValue->as);
        }
        if ($skybillValue->subAccount) {
            $skybill->setSubAccount($skybillValue->subAccount);
        }
        if ($skybillValue->toTheOrderOf) {
            $skybill->setToTheOrderOf($skybillValue->toTheOrderOf);
        }
        if ($skybillValue->alternateProductCode) {
            $skybill->setAlternateProductCode($skybillValue->alternateProductCode);
        }

        return $skybill;
    }

    /**
     * Maps an EsdValue object to an EsdValue3 object.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\EsdValue $esdValue
     *
     * @return \ChronopostShipping\StructType\EsdValue3
     */
    private function mapEsdValue(EsdValue $esdValue): EsdValue3
    {
        $closingDateTime   = $esdValue->closingDateTime;
        $retrievalDateTime = $esdValue->retrievalDateTime;

        $esdValue3 = new EsdValue3();
        $esdValue3->setClosingDateTime(
            $closingDateTime->format('Y-m-d\TH:i:s'),
        );
        $esdValue3->setHeight(0);
        $esdValue3->setLength(0);
        $esdValue3->setWidth(0);
        $esdValue3->setRetrievalDateTime(
            $retrievalDateTime->format('Y-m-d\TH:i:s'),
        );
        $esdValue3->setShipperBuildingFloor(
            $esdValue->shipperBuildingFloor,
        );
        $esdValue3->setShipperCarriesCode(
            $esdValue->shipperCarriesCode,
        );
        $esdValue3->setShipperServiceDirection(
            $esdValue->shipperServiceDirection,
        );
        $esdValue3->setSpecificInstructions(
            $esdValue->specificInstructions,
        );
        $esdValue3->setLtAImprimerParChronopost(
            $esdValue->ltShouldBePrintedByChronopost,
        );
        $esdValue3->setNombreDePassageMaximum(
            $esdValue->maximumPasses,
        );
        $esdValue3->setRefEsdClient(
            $esdValue->esdClientReference,
        );

        return $esdValue3;
    }

    /**
     * Maps a ReferenceValue object to a RefValueV2 object.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ReferenceValue $referenceValue
     *
     * @return \ChronopostShipping\StructType\RefValueV2
     */
    private function mapReferenceValue(ReferenceValue $referenceValue): RefValueV2
    {
        $refValue = new RefValueV2(
            $referenceValue->relayIdentifier,
        );
        $refValue->setCustomerSkybillNumber($referenceValue->customerSkyBillNumber);
        $refValue->setRecipientRef($referenceValue->recipientReference);
        $refValue->setShipperRef($referenceValue->shipperReference);

        return $refValue;
    }

    /**
     * Maps a ScheduledValue object to a ScheduledValueChronopost object.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ScheduledValue $scheduledValue
     *
     * @return \ChronopostShipping\StructType\ScheduledValue
     */
    private function mapScheduledValue(ScheduledValue $scheduledValue): ScheduledValueChronopost
    {
        return new ScheduledValueChronopost(
            $this->mapAppointmentValue($scheduledValue->appointement),
            $scheduledValue->expirationDate?->format('Y-m-d\TH:i:s'),
            $scheduledValue->expirationDate?->format('Y-m-d\TH:i:s'),
        );
    }

    /**
     * Maps an AppointementValue object to an AppointmentValueChronopost object.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\AppointementValue $appointementValue
     *
     * @return \ChronopostShipping\StructType\AppointmentValue
     */
    private function mapAppointmentValue(AppointementValue $appointementValue): AppointmentValueChronopost
    {
        return new AppointmentValueChronopost(
            $appointementValue->end->format('Y-m-d\TH:i:s'),
            $appointementValue->start->format('Y-m-d\TH:i:s'),
            // Niveau tarifaire, champ fixe : N1 .
            'N1',
        );
    }

    /**
     * Maps SkyBillParameters to SkybillParamsValueV2.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\SkyBillParameters $skyBillParameters
     *
     * @return \ChronopostShipping\StructType\SkybillParamsValueV2
     */
    private function mapParameters(SkyBillParameters $skyBillParameters): SkybillParamsValueV2
    {
        $mode        = $skyBillParameters->mode;
        $reservation = $skyBillParameters->reservation;
        $paramsV2    = new SkybillParamsValueV2(
            $reservation->value,
        );
        $paramsV2->setMode($mode->value);
        $paramsV2->setDuplicata($skyBillParameters->duplicata ? 'Y' : 'N');

        return $paramsV2;
    }

    /**
     * Maps a RecipientValue object to a RecipientValueV2 object.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\RecipientValue $recipientValue
     *
     * @return \ChronopostShipping\StructType\RecipientValueV2
     */
    private function mapRecipientValue(RecipientValue $recipientValue): RecipientValueV2
    {
        $recipientType = $recipientValue->recipientType;
        $country       = $recipientValue->country;
        $phone         = $recipientValue->phone;
        $mobilePhone   = $recipientValue->mobilePhone;
        $postCode      = $recipientValue->postCode;

        $recipientValueV2 = new RecipientValueV2(
            (string) ($recipientType->value),
        );
        $recipientValueV2->setRecipientAdress1($recipientValue->address1);
        $recipientValueV2->setRecipientAdress2($recipientValue->address2);
        $recipientValueV2->setRecipientCity($recipientValue->city);
        $recipientValueV2->setRecipientContactName($recipientValue->contactName);
        $recipientValueV2->setRecipientCountry($country->getCode());
        $recipientValueV2->setRecipientEmail($recipientValue->email);
        $recipientValueV2->setRecipientName($recipientValue->name);
        $recipientValueV2->setRecipientName2($recipientValue->name2);
        $recipientValueV2->setRecipientMobilePhone($mobilePhone?->getInternationalPhoneNumber());
        $recipientValueV2->setRecipientPhone($phone?->getInternationalPhoneNumber());
        $recipientValueV2->setRecipientZipCode($postCode->getPostCode());
        $recipientValueV2->setRecipientPreAlert($recipientValue->recipientPreAlert);

        return $recipientValueV2;
    }

    /**
     * Maps a CustomerValue object to a CustomerValueChronopost object.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\CustomerValue $customerValue
     *
     * @return \ChronopostShipping\StructType\CustomerValue
     */
    private function mapCustomerValue(CustomerValue $customerValue): CustomerValueChronopost
    {
        $civility    = $customerValue->civility;
        $country     = $customerValue->country;
        $phone       = $customerValue->phone;
        $mobilePhone = $customerValue->mobilePhone;
        $postCode    = $customerValue->postCode;

        return new CustomerValueChronopost(
            $customerValue->address1,
            $customerValue->address2,
            $customerValue->city,
            $civility->value,
            $customerValue->contactName,
            $country->getCode(),
            $country->getDisplayableName(),
            $customerValue->email,
            $mobilePhone?->getInternationalPhoneNumber(),
            $customerValue->name,
            $customerValue->name2,
            $phone?->getInternationalPhoneNumber(),
            // Pre alert is not used in this context.
            null,
            $postCode->getPostCode(),
            $customerValue->printAsSender ? 'Y' : 'N',
        );
    }

    /**
     * Maps a ShipperValue object to a ShipperValueV2 object.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ShipperValue $shipperValue
     *
     * @return \ChronopostShipping\StructType\ShipperValueV2
     */
    private function mapShipperValue(ShipperValue $shipperValue): ShipperValueV2
    {
        $shipperType = $shipperValue->shipperType;
        $postCode    = $shipperValue->postCode;
        $civility    = $shipperValue->civility;
        $country     = $shipperValue->country;
        $phone       = $shipperValue->phone;
        $mobilePhone = $shipperValue->mobilePhone;

        $shipperValueV2 = new ShipperValueV2(
            (string) ($shipperType->value),
        );

        $shipperValueV2->setShipperAdress1($shipperValue->address1);
        $shipperValueV2->setShipperAdress2($shipperValue->address2);
        $shipperValueV2->setShipperCity($shipperValue->city);
        $shipperValueV2->setShipperCivility($civility->value);
        $shipperValueV2->setShipperContactName($shipperValue->contactName);
        $shipperValueV2->setShipperCountry($country->getCode());
        $shipperValueV2->setShipperEmail($shipperValue->email);
        $shipperValueV2->setShipperName($shipperValue->name);
        $shipperValueV2->setShipperName2($shipperValue->name2);
        $shipperValueV2->setShipperMobilePhone($mobilePhone?->getInternationalPhoneNumber());
        $shipperValueV2->setShipperPhone($phone?->getInternationalPhoneNumber());
        $shipperValueV2->setShipperZipCode($postCode->getPostCode());
        $shipperValueV2->setShipperPreAlert($shipperValue->shipperPreAlert);

        return $shipperValueV2;
    }

    /**
     * Maps a ShipperValue to a V1 ShipperValue (Chronopost).
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ShipperValue $shipperValue
     *
     * @return \ChronopostShipping\StructType\ShipperValue
     */
    private function mapShipperValueV1(ShipperValue $shipperValue): ShipperValueChronopost
    {
        $country     = $shipperValue->country;
        $postCode    = $shipperValue->postCode;
        $civility    = $shipperValue->civility;
        $phone       = $shipperValue->phone;
        $mobilePhone = $shipperValue->mobilePhone;

        return new ShipperValueChronopost(
            shipperAdress1: $shipperValue->address1,
            shipperAdress2: $shipperValue->address2,
            shipperCity: $shipperValue->city,
            shipperCivility: $civility->value,
            shipperContactName: $shipperValue->contactName,
            shipperCountry: $country->getCode(),
            shipperCountryName: $country->getDisplayableName(),
            shipperEmail: $shipperValue->email,
            shipperMobilePhone: $mobilePhone?->getInternationalPhoneNumber(),
            shipperName: $shipperValue->name,
            shipperName2: $shipperValue->name2,
            shipperPhone: $phone?->getInternationalPhoneNumber(),
            shipperPreAlert: $shipperValue->shipperPreAlert,
            shipperZipCode: $postCode->getPostCode(),
        );
    }

    /**
     * Maps a RecipientValue to a V1 RecipientValue (Chronopost).
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\RecipientValue $recipientValue
     *
     * @return \ChronopostShipping\StructType\RecipientValue
     */
    private function mapRecipientValueV1(RecipientValue $recipientValue): RecipientValueChronopost
    {
        $country     = $recipientValue->country;
        $postCode    = $recipientValue->postCode;
        $phone       = $recipientValue->phone;
        $mobilePhone = $recipientValue->mobilePhone;

        return new RecipientValueChronopost(
            recipientAdress1: $recipientValue->address1,
            recipientAdress2: $recipientValue->address2,
            recipientCity: $recipientValue->city,
            recipientContactName: $recipientValue->contactName,
            recipientCountry: $country->getCode(),
            recipientCountryName: $country->getDisplayableName(),
            recipientEmail: $recipientValue->email,
            recipientMobilePhone: $mobilePhone?->getInternationalPhoneNumber(),
            recipientName: $recipientValue->name,
            recipientName2: $recipientValue->name2,
            recipientPhone: $phone?->getInternationalPhoneNumber(),
            recipientPreAlert: $recipientValue->recipientPreAlert,
            recipientZipCode: $postCode->getPostCode(),
        );
    }

    /**
     * Maps a ReferenceValue to a V1 RefValue.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ReferenceValue $referenceValue
     *
     * @return \ChronopostShipping\StructType\RefValue
     */
    private function mapReferenceValueV1(ReferenceValue $referenceValue): RefValue
    {
        return new RefValue(
            customerSkybillNumber: $referenceValue->customerSkyBillNumber,
            recipientRef: $referenceValue->recipientReference,
            shipperRef: $referenceValue->shipperReference,
        );
    }

    /**
     * Maps an EsdValue to a V1 EsdValue (Chronopost).
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\EsdValue $esdValue
     *
     * @return \ChronopostShipping\StructType\EsdValue
     */
    private function mapEsdValueV1(EsdValue $esdValue): EsdValueChronopost
    {
        return new EsdValueChronopost(
            closingDateTime: $esdValue->closingDateTime->format('Y-m-d\TH:i:s'),
            height: 0,
            length: 0,
            retrievalDateTime: $esdValue->retrievalDateTime->format('Y-m-d\TH:i:s'),
            shipperBuildingFloor: $esdValue->shipperBuildingFloor,
            shipperCarriesCode: $esdValue->shipperCarriesCode,
            shipperServiceDirection: $esdValue->shipperServiceDirection,
            specificInstructions: $esdValue->specificInstructions,
            width: 0,
        );
    }

    /**
     * Maps an EsdValue to an EsdWithRefClientValue.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\EsdValue $esdValue
     *
     * @return \ChronopostShipping\StructType\EsdWithRefClientValue
     */
    private function mapEsdWithRefClient(EsdValue $esdValue): EsdWithRefClientValue
    {
        $esdWith = new EsdWithRefClientValue(
            ltAImprimerParChronopost: $esdValue->ltShouldBePrintedByChronopost,
            nombreDePassageMaximum: $esdValue->maximumPasses,
            refEsdClient: $esdValue->esdClientReference,
        );
        $esdWith->setClosingDateTime($esdValue->closingDateTime->format('Y-m-d\TH:i:s'));
        $esdWith->setRetrievalDateTime($esdValue->retrievalDateTime->format('Y-m-d\TH:i:s'));
        $esdWith->setShipperBuildingFloor($esdValue->shipperBuildingFloor);
        $esdWith->setShipperCarriesCode($esdValue->shipperCarriesCode);
        $esdWith->setShipperServiceDirection($esdValue->shipperServiceDirection);
        $esdWith->setSpecificInstructions($esdValue->specificInstructions);
        $esdWith->setHeight(0);
        $esdWith->setLength(0);
        $esdWith->setWidth(0);

        return $esdWith;
    }

    /**
     * Maps an EsdValue to an EsdWithRefClientValueV2.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\EsdValue $esdValue
     *
     * @return \ChronopostShipping\StructType\EsdWithRefClientValueV2
     */
    private function mapEsdWithRefClientV2(EsdValue $esdValue): EsdWithRefClientValueV2
    {
        $esdWithV2 = new EsdWithRefClientValueV2();
        $esdWithV2->setClosingDateTime($esdValue->closingDateTime->format('Y-m-d\TH:i:s'));
        $esdWithV2->setRetrievalDateTime($esdValue->retrievalDateTime->format('Y-m-d\TH:i:s'));
        $esdWithV2->setShipperBuildingFloor($esdValue->shipperBuildingFloor);
        $esdWithV2->setShipperCarriesCode($esdValue->shipperCarriesCode);
        $esdWithV2->setShipperServiceDirection($esdValue->shipperServiceDirection);
        $esdWithV2->setSpecificInstructions($esdValue->specificInstructions);
        $esdWithV2->setLtAImprimerParChronopost($esdValue->ltShouldBePrintedByChronopost);
        $esdWithV2->setNombreDePassageMaximum($esdValue->maximumPasses);
        $esdWithV2->setRefEsdClient($esdValue->esdClientReference);
        $esdWithV2->setHeight(0);
        $esdWithV2->setLength(0);
        $esdWithV2->setWidth(0);

        return $esdWithV2;
    }

    /**
     * Maps a SkyBillValue to a SkybillWithDimensionsValueV3 (for shippingV7).
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\SkyBillValue $skybillValue
     *
     * @return \ChronopostShipping\StructType\SkybillWithDimensionsValueV3
     * @phpcs:disable Generic.Metrics.CyclomaticComplexity.TooHigh
     */
    private function mapSkybillValueV3(SkyBillValue $skybillValue): SkybillDimV3
    {
        // phpcs:enable
        $skybill = new SkybillDimV3(
            subAccount: $skybillValue->subAccount,
            toTheOrderOf: $skybillValue->toTheOrderOf,
        );

        $this->populateBaseSkybillFields($skybill, $skybillValue);
        $skybill->setAs($skybillValue->as);
        $skybill->setHeight($skybillValue->height);
        $skybill->setLength($skybillValue->length);
        $skybill->setWidth($skybillValue->width);

        return $skybill;
    }

    /**
     * Maps a SkyBillValue to a SkybillWithDimensionsValueV8 (for shippingMultiParcelV7).
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\SkyBillValue $skybillValue
     *
     * @return \ChronopostShipping\StructType\SkybillWithDimensionsValueV8
     * @phpcs:disable Generic.Metrics.CyclomaticComplexity.TooHigh
     */
    private function mapSkybillValueV8(SkyBillValue $skybillValue): SkybillDimV8
    {
        // phpcs:enable
        $skybill = new SkybillDimV8();

        $this->populateBaseSkybillFields($skybill, $skybillValue);
        $skybill->setAs($skybillValue->as);
        $skybill->setSubAccount($skybillValue->subAccount);
        $skybill->setToTheOrderOf($skybillValue->toTheOrderOf);
        $skybill->setHeight($skybillValue->height);
        $skybill->setLength($skybillValue->length);
        $skybill->setWidth($skybillValue->width);

        if ($skybillValue->alternateProductCode) {
            $skybill->setAlternateProductCode($skybillValue->alternateProductCode);
        }

        return $skybill;
    }

    /**
     * Maps a SkyBillValue to a SkybillValueV2 (for reservation methods).
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\SkyBillValue $skybillValue
     *
     * @return \ChronopostShipping\StructType\SkybillValueV2
     * @phpcs:disable Generic.Metrics.CyclomaticComplexity.TooHigh
     */
    private function mapSkybillValueV2(SkyBillValue $skybillValue): SkybillValueV2Chronopost
    {
        // phpcs:enable
        $skybill = new SkybillValueV2Chronopost(
            as: $skybillValue->as,
        );

        $this->populateBaseSkybillFields($skybill, $skybillValue);

        return $skybill;
    }

    /**
     * Maps a SkyBillValue to a SkybillWithDimensionsValueV2 (for multi reservation).
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\SkyBillValue $skybillValue
     *
     * @return \ChronopostShipping\StructType\SkybillWithDimensionsValueV2
     * @phpcs:disable Generic.Metrics.CyclomaticComplexity.TooHigh
     */
    private function mapSkybillValueDimV2(SkyBillValue $skybillValue): SkybillDimV2
    {
        // phpcs:enable
        $skybill = new SkybillDimV2(
            as: $skybillValue->as,
        );

        $this->populateBaseSkybillFields($skybill, $skybillValue);
        $skybill->setHeight($skybillValue->height);
        $skybill->setLength($skybillValue->length);
        $skybill->setWidth($skybillValue->width);

        return $skybill;
    }

    /**
     * Maps a SkyBillValue to a base SkybillValue (for ESD Only V2).
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\SkyBillValue $skybillValue
     *
     * @return \ChronopostShipping\StructType\SkybillValue
     * @phpcs:disable Generic.Metrics.CyclomaticComplexity.TooHigh
     */
    private function mapSkybillValueBase(SkyBillValue $skybillValue): SkybillValueChronopost
    {
        // phpcs:enable
        $skybill = new SkybillValueChronopost();

        $this->populateBaseSkybillFields($skybill, $skybillValue);

        return $skybill;
    }

    /**
     * Populates base skybill fields common to all skybill types.
     *
     * @param SkybillValueChronopost                                         $skybill
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\SkyBillValue $skybillValue
     *
     * @return void
     * @phpcs:disable Generic.Metrics.CyclomaticComplexity.TooHigh
     */
    private function populateBaseSkybillFields(
        SkybillValueChronopost $skybill,
        SkyBillValue $skybillValue,
    ): void {
        // phpcs:enable
        $shipDateTime  = $skybillValue->shipDateTime;
        $parcelContent = $skybillValue->content;

        if ($parcelContent !== null) {
            $skybill->setContent1($parcelContent->content1);
            if (!empty($parcelContent->content2)) {
                $skybill->setContent2($parcelContent->content2);
            }
            if (!empty($parcelContent->content3)) {
                $skybill->setContent3($parcelContent->content3);
            }
            if (!empty($parcelContent->content4)) {
                $skybill->setContent4($parcelContent->content4);
            }
            if (!empty($parcelContent->content5)) {
                $skybill->setContent5($parcelContent->content5);
            }
        }

        $skybill->setBulkNumber((string) ($skybillValue->bulkNumber));
        $skybill->setCodCurrency($skybillValue->codCurrency);
        $skybill->setCodValue($skybillValue->codValue);
        $skybill->setCustomsCurrency($skybillValue->customsCurrency);
        $skybill->setCustomsValue($skybillValue->customsValue);
        $skybill->setInsuredCurrency($skybillValue->insuredCurrency);
        $skybill->setInsuredValue($skybillValue->insuredValue);
        $skybill->setMasterSkybillNumber($skybillValue->masterSkybillNumber);
        $skybill->setEvtCode('DC');
        $skybill->setObjectType($skybillValue->objectType->value);
        $skybill->setProductCode($skybillValue->productCode);
        $skybill->setService($skybillValue->serviceCode);

        if ($shipDateTime !== null) {
            $skybill->setShipDate($shipDateTime->format('Y-m-d\TH:i:s'));
            $skybill->setShipHour((int) ($shipDateTime->format('H')));
        }

        $skybill->setSkybillRank((string) ($skybillValue->skybillRank));
        $skybill->setWeight((float) (number_format($skybillValue->weight, 2, '.', '')));
        $skybill->setWeightUnit('KGM');
    }

    /**
     * Maps SkyBillParameters to V1 SkybillParamsValue.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\SkyBillParameters $skyBillParameters
     *
     * @return \ChronopostShipping\StructType\SkybillParamsValue
     */
    private function mapParametersV1(SkyBillParameters $skyBillParameters): SkybillParamsValue
    {
        return new SkybillParamsValue(
            duplicata: $skyBillParameters->duplicata ? 'Y' : 'N',
            mode: $skyBillParameters->mode->value,
        );
    }
}
