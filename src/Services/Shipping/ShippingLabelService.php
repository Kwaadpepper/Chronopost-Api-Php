<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Services\Shipping;

use ChronopostShipping\ClassMap;
use ChronopostShipping\ServiceType\Get;
use ChronopostShipping\StructType\GetReservedSkybill;
use ChronopostShipping\StructType\GetReservedSkybillWithType;
use ChronopostShipping\StructType\GetReservedSkybillWithTypeAndMode;
use ChronopostShipping\StructType\GetReservedSkybillWithTypeAndModeAuth;
use ChronopostShipping\StructType\GetReservedSkybillWithTypeAndModeByReservation;
use ChronopostShipping\StructType\GetRouting;
use ChronopostShipping\StructType\GetShippingInformation;
use ChronopostShipping\StructType\GetSkybill;
use ChronopostShipping\StructType\HeaderValue;
use ChronopostShipping\StructType\RecipientValue;
use ChronopostShipping\StructType\ShipperValue;
use ChronopostShipping\StructType\SkybillValueBase;
use Kwaadpepper\ChronopostApiPhp\Contracts\ShippingLabelServiceInterface;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\RoutingInfo;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\ShippingInformation;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\SkybillLabel;
use Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError;
use Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException;
use Kwaadpepper\ChronopostApiPhp\Factory\RoutingInfoFactory;
use Kwaadpepper\ChronopostApiPhp\Factory\ShippingInformationFactory;
use Kwaadpepper\ChronopostApiPhp\Factory\SkybillLabelFactory;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;
use WsdlToPhp\PackageBase\SoapClientInterface;

class ShippingLabelService implements ShippingLabelServiceInterface
{
    private Get $getService;

    /**
     * Shipping service soap url.
     *
     * @var string
     */
    protected static string $serviceUrl = 'https://ws.chronopost.fr/shipping-cxf/ShippingServiceWS?wsdl';

    /**
     * @param array<string, mixed>                     $soapOptions
     * @param \ChronopostShipping\ServiceType\Get|null $getService
     */
    public function __construct(
        #[\SensitiveParameter] private AccountNumber $accountNumber,
        #[\SensitiveParameter] private Password $password,
        array $soapOptions = [],
        ?Get $getService = null,
    ) {
        if ($getService !== null) {
            $this->getService = $getService;
            return;
        }

        $soapOptions = array_merge(
            $soapOptions,
            [
                SoapClientInterface::WSDL_URL => static::$serviceUrl,
                SoapClientInterface::WSDL_CLASSMAP => ClassMap::get(),
            ],
        );

        $this->getService = new Get($soapOptions);
    }

    /**
     * @param string                                                   $numberSearch  Skybill number.
     * @param string                                                   $mode          Label mode (e.g. PDF).
     * @param string|null                                              $key           Authentication key.
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\SkybillLabel
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the SOAP call fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException If the API returns an error.
     */
    public function getSkybill(
        string $numberSearch,
        string $mode = 'PDF',
        ?string $key = null,
    ): SkybillLabel {
        $result = $this->getService->getSkybill(new GetSkybill(
            numberSearch: $numberSearch,
            mode: $mode,
            key: $key ?? $this->password->getPassword(),
            account: $this->accountNumber->getAccountNumber(),
        ));

        $return = $this->extractReturnOrThrow(
            $result,
            Get::class . '::getSkybill',
            'Failed to get result from getSkybill service, null response',
        );

        /** @var \ChronopostShipping\StructType\ResultGetReservedSkybillWithTypeValue $return */
        $this->assertNoShippingError($return->getErrorCode(), $return->getErrorMessage());

        return (new SkybillLabelFactory())->createWithIdentifier($return, $numberSearch);
    }

    /**
     * @param string                                                   $reservationNumber Reservation number.
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\SkybillLabel
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the SOAP call fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException If the API returns an error.
     */
    public function getReservedSkybill(
        string $reservationNumber,
    ): SkybillLabel {
        $result = $this->getService->getReservedSkybill(new GetReservedSkybill(
            reservationNumber: $reservationNumber,
        ));

        $return = $this->extractReturnOrThrow(
            $result,
            Get::class . '::getReservedSkybill',
            'Failed to get result from getReservedSkybill service, null response',
        );

        /** @var \ChronopostShipping\StructType\ResultGetReservedSkybillValue $return */
        $this->assertNoShippingError($return->getErrorCode(), $return->getErrorMessage());

        return (new SkybillLabelFactory())->createWithIdentifier($return, $reservationNumber);
    }

    /**
     * @param string                                                   $reservationNumber Reservation number.
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\SkybillLabel
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the SOAP call fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException If the API returns an error.
     */
    public function getReservedSkybillWithType(
        string $reservationNumber,
    ): SkybillLabel {
        $result = $this->getService->getReservedSkybillWithType(new GetReservedSkybillWithType(
            reservationNumber: $reservationNumber,
        ));

        $return = $this->extractReturnOrThrow(
            $result,
            Get::class . '::getReservedSkybillWithType',
            'Failed to get result from getReservedSkybillWithType service, null response',
        );

        /** @var \ChronopostShipping\StructType\ResultGetReservedSkybillWithTypeValue $return */
        $this->assertNoShippingError($return->getErrorCode(), $return->getErrorMessage());

        return (new SkybillLabelFactory())->createWithIdentifier($return, $reservationNumber);
    }

    /**
     * @param string                                                   $reservationNumber Reservation number.
     * @param string                                                   $mode              Label mode.
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\SkybillLabel
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the SOAP call fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException If the API returns an error.
     */
    public function getReservedSkybillWithTypeAndMode(
        string $reservationNumber,
        string $mode,
    ): SkybillLabel {
        $result = $this->getService->getReservedSkybillWithTypeAndMode(new GetReservedSkybillWithTypeAndMode(
            reservationNumber: $reservationNumber,
            mode: $mode,
        ));

        $return = $this->extractReturnOrThrow(
            $result,
            Get::class . '::getReservedSkybillWithTypeAndMode',
            'Failed to get result from getReservedSkybillWithTypeAndMode service, null response',
        );

        /** @var \ChronopostShipping\StructType\ResultGetReservedSkybillWithTypeValue $return */
        $this->assertNoShippingError($return->getErrorCode(), $return->getErrorMessage());

        return (new SkybillLabelFactory())->createWithIdentifier($return, $reservationNumber);
    }

    /**
     * @param string                                                   $numberSearch  Search number.
     * @param string                                                   $mode          Label mode.
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\SkybillLabel
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the SOAP call fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException If the API returns an error.
     */
    public function getReservedSkybillWithTypeAndModeAuth(
        string $numberSearch,
        string $mode,
    ): SkybillLabel {
        $result = $this->getService->getReservedSkybillWithTypeAndModeAuth(new GetReservedSkybillWithTypeAndModeAuth(
            numberSearch: $numberSearch,
            mode: $mode,
            accountNumber: (int) ($this->accountNumber->getAccountNumber()),
            password: $this->password->getPassword(),
        ));

        $return = $this->extractReturnOrThrow(
            $result,
            Get::class . '::getReservedSkybillWithTypeAndModeAuth',
            'Failed to get result from getReservedSkybillWithTypeAndModeAuth service, null response',
        );

        /** @var \ChronopostShipping\StructType\ResultGetReservedSkybillWithTypeValue $return */
        $this->assertNoShippingError($return->getErrorCode(), $return->getErrorMessage());

        return (new SkybillLabelFactory())->createWithIdentifier($return, $numberSearch);
    }

    /**
     * @param string                                                   $reservationNumber Reservation number.
     * @param string                                                   $mode              Label mode.
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\SkybillLabel
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the SOAP call fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException If the API returns an error.
     */
    public function getReservedSkybillWithTypeAndModeByReservation(
        string $reservationNumber,
        string $mode,
    ): SkybillLabel {
        $result = $this->getService->getReservedSkybillWithTypeAndModeByReservation(
            new GetReservedSkybillWithTypeAndModeByReservation(
                reservationNumber: $reservationNumber,
                mode: $mode,
            ),
        );

        $return = $this->extractReturnOrThrow(
            $result,
            Get::class . '::getReservedSkybillWithTypeAndModeByReservation',
            'Failed to get result from getReservedSkybillWithTypeAndModeByReservation service, null response',
        );

        /** @var \ChronopostShipping\StructType\ResultGetReservedSkybillWithTypeValue $return */
        $this->assertNoShippingError($return->getErrorCode(), $return->getErrorMessage());

        return (new SkybillLabelFactory())->createWithIdentifier($return, $reservationNumber);
    }

    /**
     * @param string                                                   $shipperDepot  Shipper depot code.
     * @param string                                                   $countryCode   Destination country code.
     * @param string                                                   $zipCode       Destination zip code.
     * @param string|null                                              $socode        Optional SO code.
     * @param string|null                                              $ascode        Optional AS code.
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\RoutingInfo
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the SOAP call fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException If the API returns an error.
     */
    public function getRouting(
        string $shipperDepot,
        string $countryCode,
        string $zipCode,
        ?string $socode = null,
        ?string $ascode = null,
    ): RoutingInfo {
        $result = $this->getService->getRouting(new GetRouting(
            accountNumber: $this->accountNumber->getAccountNumber(),
            password: $this->password->getPassword(),
            shipperDepot: $shipperDepot,
            countryCode: $countryCode,
            zipCode: $zipCode,
            socode: $socode,
            ascode: $ascode,
        ));

        $return = $this->extractReturnOrThrow(
            $result,
            Get::class . '::getRouting',
            'Failed to get result from getRouting service, null response',
        );

        /** @var \ChronopostShipping\StructType\ResultGetRouting $return */
        $this->assertNoShippingError($return->getErrorCode(), $return->getErrorMessage());

        return (new RoutingInfoFactory())->create($return);
    }

    /**
     * @param \ChronopostShipping\StructType\ShipperValue              $shipperValue     Shipper payload.
     * @param \ChronopostShipping\StructType\RecipientValue            $recipientValue   Recipient payload.
     * @param \ChronopostShipping\StructType\SkybillValueBase          $skybillValueBase Skybill payload.
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\ShippingInformation
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the SOAP call fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException If the API returns an error.
     */
    public function getShippingInformation(
        ShipperValue $shipperValue,
        RecipientValue $recipientValue,
        SkybillValueBase $skybillValueBase,
    ): ShippingInformation {
        $parameters = new GetShippingInformation(
            headerValue: new HeaderValue((int) ($this->accountNumber->getAccountNumber()), 'CHRFR', ''),
            shipperValue: $shipperValue,
            recipientValue: $recipientValue,
            skybillValueBase: $skybillValueBase,
            password: $this->password->getPassword(),
        );

        $result = $this->getService->getShippingInformation($parameters);

        $return = $this->extractReturnOrThrow(
            $result,
            Get::class . '::getShippingInformation',
            'Failed to get result from getShippingInformation service, null response',
        );

        /** @var \ChronopostShipping\StructType\ResultShippingInfo $return */
        $error = $return->getError();

        if ($error !== null && $error->getErrorCode() !== null && $error->getErrorCode() !== 0) {
            throw new ShippingException(
                (string) $error->getErrorMessage(),
                (int) $error->getErrorCode(),
            );
        }

        return (new ShippingInformationFactory())->create($return);
    }

    /**
     * @param boolean|object $result      SOAP response object or false.
     * @param string         $method      SOAP method name.
     * @param string         $nullMessage Message when return payload is null.
     * @return object
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If SOAP call or payload is invalid.
     */
    private function extractReturnOrThrow(bool|object $result, string $method, string $nullMessage): object
    {
        if ($result === false) {
            $lastError = $this->getService->getLastErrorForMethod(methodName: $method);
            throw new ApiError('Failed to call shipping label service', $lastError);
        }

        if (!method_exists($result, 'getReturn')) {
            throw new ApiError('Invalid response from shipping label service');
        }

        /** @var object|null $return */
        $return = $result->getReturn();

        if ($return === null) {
            throw new ApiError($nullMessage);
        }

        return $return;
    }

    /**
     * @param integer|null $errorCode    API error code.
     * @param string|null  $errorMessage API error message.
     * @return void
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException If API reported an error.
     */
    private function assertNoShippingError(?int $errorCode, ?string $errorMessage): void
    {
        if ($errorCode !== null && $errorCode !== 0) {
            throw new ShippingException((string) $errorMessage, $errorCode);
        }
    }
}
