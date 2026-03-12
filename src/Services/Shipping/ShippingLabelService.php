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
use ChronopostShipping\StructType\RecipientValue as RecipientValueChronopost;
use ChronopostShipping\StructType\ResultGetReservedSkybillValue;
use ChronopostShipping\StructType\ResultGetReservedSkybillWithTypeValue;
use ChronopostShipping\StructType\ResultGetRouting;
use ChronopostShipping\StructType\ResultShippingInfo;
use ChronopostShipping\StructType\ShipperValue as ShipperValueChronopost;
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
     * @param array<string, mixed>                  $soapOptions
     * @param \ChronopostShipping\ServiceType\Get|null $getService
     */
    public function __construct(array $soapOptions = [], ?Get $getService = null)
    {
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
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException
     */
    public function getSkybill(
        AccountNumber $accountNumber,
        Password $password,
        string $numberSearch,
        string $mode = 'PDF',
        ?string $key = null,
    ): SkybillLabel {
        $result = $this->getService->getSkybill(new GetSkybill(
            numberSearch: $numberSearch,
            mode: $mode,
            key: $key ?? $password->getPassword(),
            account: $accountNumber->getAccountNumber(),
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
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException
     */
    public function getReservedSkybill(
        AccountNumber $accountNumber,
        Password $password,
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
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException
     */
    public function getReservedSkybillWithType(
        AccountNumber $accountNumber,
        Password $password,
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
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException
     */
    public function getReservedSkybillWithTypeAndMode(
        AccountNumber $accountNumber,
        Password $password,
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
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException
     */
    public function getReservedSkybillWithTypeAndModeAuth(
        AccountNumber $accountNumber,
        Password $password,
        string $numberSearch,
        string $mode,
    ): SkybillLabel {
        $result = $this->getService->getReservedSkybillWithTypeAndModeAuth(new GetReservedSkybillWithTypeAndModeAuth(
            numberSearch: $numberSearch,
            mode: $mode,
            accountNumber: intval($accountNumber->getAccountNumber()),
            password: $password->getPassword(),
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
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException
     */
    public function getReservedSkybillWithTypeAndModeByReservation(
        AccountNumber $accountNumber,
        Password $password,
        string $reservationNumber,
        string $mode,
    ): SkybillLabel {
        $result = $this->getService->getReservedSkybillWithTypeAndModeByReservation(
            new GetReservedSkybillWithTypeAndModeByReservation(
                reservationNumber: $reservationNumber,
                mode: $mode,
            )
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
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException
     */
    public function getRouting(
        AccountNumber $accountNumber,
        Password $password,
        string $shipperDepot,
        string $countryCode,
        string $zipCode,
        ?string $socode = null,
        ?string $ascode = null,
    ): RoutingInfo {
        $result = $this->getService->getRouting(new GetRouting(
            accountNumber: $accountNumber->getAccountNumber(),
            password: $password->getPassword(),
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
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException
     */
    public function getShippingInformation(
        AccountNumber $accountNumber,
        Password $password,
        ShipperValueChronopost $shipperValue,
        RecipientValueChronopost $recipientValue,
        SkybillValueBase $skybillValueBase,
    ): ShippingInformation {
        $parameters = new GetShippingInformation(
            headerValue: new HeaderValue(intval($accountNumber->getAccountNumber()), 'CHRFR', ''),
            shipperValue: $shipperValue,
            recipientValue: $recipientValue,
            skybillValueBase: $skybillValueBase,
            password: $password->getPassword(),
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
     * @param bool|object $result
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

    private function assertNoShippingError(?int $errorCode, ?string $errorMessage): void
    {
        if ($errorCode !== null && $errorCode !== 0) {
            throw new ShippingException((string) $errorMessage, $errorCode);
        }
    }
}
