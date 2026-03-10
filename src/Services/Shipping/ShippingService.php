<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Services\Shipping;

use ChronopostShipping\ClassMap;
use ChronopostShipping\ServiceType\Shipping;
use ChronopostShipping\StructType\AppointmentValue as AppointmentValueChronopost;
use ChronopostShipping\StructType\CustomerValue as CustomerValueChronopost;
use ChronopostShipping\StructType\EsdValue3;
use ChronopostShipping\StructType\HeaderValue;
use ChronopostShipping\StructType\RecipientValueV2;
use ChronopostShipping\StructType\RefValueV2;
use ChronopostShipping\StructType\ScheduledValue as ScheduledValueChronopost;
use ChronopostShipping\StructType\ShipperValueV2;
use ChronopostShipping\StructType\ShippingMultiParcelV4;
use ChronopostShipping\StructType\SkybillParamsValueV2;
use ChronopostShipping\StructType\SkybillWithDimensionsValueV6;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\MultiParcelV4;
use Kwaadpepper\ChronopostApiPhp\Enums\SkyBillOutputMode;
use Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError;
use Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\EsdException;
use Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException;
use Kwaadpepper\ChronopostApiPhp\Factory\MultiParcelV4Factory;
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

class ShippingService
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
     * @param array $soapOptions Additional options for the soap client.
     */
    public function __construct(
        array $soapOptions = []
    ) {
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
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber                 $accountNumber
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Password                      $password
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
        ?SkyBillParameters $skyBillParameters = null
    ): MultiParcelV4 {
        return $this->multiParcelV4(
            accountNumber: $accountNumber,
            password: $password,
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
     * Creates a multi-parcel shipment with the provided values.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber                 $accountNumber
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Password                      $password
     * @param array                                                                    $skybillValues
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\CustomerValue          $customerValue
     * @param array                                                                    $shippersValues
     * @param array                                                                    $recipientsValues
     * @param array                                                                    $referenceValues
     * @param array                                                                    $scheduledValues
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
     * @phpcs:disable Squiz.Commenting.FunctionCommentThrowTag.WrongNumber
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
        ?SkyBillParameters $skyBillParameters = null
    ): MultiParcelV4 {
        // phpcs:enable

        if ($multiParcel && count($recipientsValues) > 1) {
            throw new \InvalidArgumentException(
                'When using multi-parcel, you cannot provide more than one recipient.'
            );
        }

        if (count($shippersValues) > $numberOfParcel) {
            throw new \InvalidArgumentException(
                'Too many shippers values provided. It must not exceed the number of parcels.'
            );
        }

        if (count($recipientsValues) > $numberOfParcel) {
            throw new \InvalidArgumentException(
                'Too many recipients values provided. It must not exceed the number of parcels.'
            );
        }

        if (count($referenceValues) !== $numberOfParcel) {
            throw new \InvalidArgumentException(
                'The number of reference values must match the number of parcels.'
            );
        }

        if (count($skybillValues) !== $numberOfParcel) {
            throw new \InvalidArgumentException(
                'The number of skybill values must match the number of parcels.'
            );
        }

        if (count($scheduledValues) > $numberOfParcel) {
            throw new \InvalidArgumentException(
                'Too many scheduled values provided. It must not exceed the number of parcels.'
            );
        }

        $headerValue = new HeaderValue(
            intval($accountNumber->getAccountNumber()),
            'CHRFR',
            ''
        );

        $skyBillParameters = $skyBillParameters ?? new SkyBillParameters();

        $parameters = new ShippingMultiParcelV4(
            skybillParamsValue: $this->mapParameters($skyBillParameters),
            password: $password->getPassword(),
            version: '2.0',
            numberOfParcel: $numberOfParcel,
            multiParcel: $multiParcel ? 'Y' : 'N',
            modeRetour: strval($skyBillOutputMode->value),
            headerValue: $headerValue,
            esdValue: $esdValue ? $this->mapEsdValue($esdValue) : null,
            skybillValue: array_map(
                fn(SkyBillValue $skybillValue) =>
                $this->mapSkybillValue($skybillValue),
                $skybillValues
            ),
            customerValue: $this->mapCustomerValue($customerValue),
            refValue: array_map(
                fn(ReferenceValue $referenceValue) =>
                $this->mapReferenceValue($referenceValue),
                $referenceValues
            ),
            shipperValue: array_map(
                fn(ShipperValue $shipperValue) =>
                $this->mapShipperValue($shipperValue),
                $shippersValues
            ),
            recipientValue: array_map(
                fn(RecipientValue $recipientValue) =>
                $this->mapRecipientValue($recipientValue),
                $recipientsValues
            ),
            scheduledValue: array_map(
                fn(ScheduledValue $scheduledValue) =>
                $this->mapScheduledValue($scheduledValue),
                $scheduledValues
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
                    $errorMessage
                );
            }

            throw new ShippingException($errorMessage, $errorCode);
        }

        $factory = new MultiParcelV4Factory();

        return $factory->create($response);
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

        $skybill->setBulkNumber(strval($skybillValue->bulkNumber));
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
            $skybill->setShipHour(intval($shipDateTime->format('H')));
        }

        $skybill->setSkybillRank(strval($skybillValue->skybillRank));

        $skybill->setWeight(floatval(number_format(
            $skybillValue->weight,
            2,
            '.',
            ''
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
            $closingDateTime->format('Y-m-d\TH:i:s')
        );
        $esdValue3->setHeight(0);
        $esdValue3->setLength(0);
        $esdValue3->setWidth(0);
        $esdValue3->setRetrievalDateTime(
            $retrievalDateTime->format('Y-m-d\TH:i:s')
        );
        $esdValue3->setShipperBuildingFloor(
            $esdValue->shipperBuildingFloor
        );
        $esdValue3->setShipperCarriesCode(
            $esdValue->shipperCarriesCode
        );
        $esdValue3->setShipperServiceDirection(
            $esdValue->shipperServiceDirection
        );
        $esdValue3->setSpecificInstructions(
            $esdValue->specificInstructions
        );
        $esdValue3->setLtAImprimerParChronopost(
            $esdValue->ltShouldBePrintedByChronopost
        );
        $esdValue3->setNombreDePassageMaximum(
            $esdValue->maximumPasses
        );
        $esdValue3->setRefEsdClient(
            $esdValue->esdClientReference
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
            'N1'
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
            $reservation->value
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
            strval($recipientType->value),
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
            $customerValue->printAsSender ? 'Y' : 'N'
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
            strval($shipperType->value),
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
}
