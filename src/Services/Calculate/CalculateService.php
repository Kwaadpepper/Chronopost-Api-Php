<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Services\Calculate;

use ChronopostQuickCost\ClassMap;
use ChronopostQuickCost\ServiceType\Calculate;
use ChronopostQuickCost\StructType\CalculateDeliveryTime;
use ChronopostQuickCost\StructType\CalculateProducts;
use Kwaadpepper\ChronopostApiPhp\Contracts\CalculateServiceInterface;
use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\DeliveryTime;
use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\ProductList;
use Kwaadpepper\ChronopostApiPhp\Enums\ShippingType;
use Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError;
use Kwaadpepper\ChronopostApiPhp\Exceptions\Calculate\CalculateException;
use Kwaadpepper\ChronopostApiPhp\Factory\CalculateDeliveryTimeFactory;
use Kwaadpepper\ChronopostApiPhp\Factory\CalculateProductsFactory;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ProductCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ServiceCode;
use WsdlToPhp\PackageBase\SoapClientInterface;

class CalculateService implements CalculateServiceInterface
{
    /**
     * Calculate service
     */
    private Calculate $calculateService;

    /**
     * Tracking service soap url
     */
    protected static string $serviceUrl = 'https://ws.chronopost.fr/quickcost-cxf/QuickcostServiceWS?wsdl';

    /**
     * Constructor
     *
     * @param  array<string, mixed> $soapOptions Additional options for the soap client.
     */
    public function __construct(
        array $soapOptions = [],
    ) {
        $soapOptions            = array_merge(
            $soapOptions,
            [
                SoapClientInterface::WSDL_URL      => static::$serviceUrl,
                SoapClientInterface::WSDL_CLASSMAP => ClassMap::get(),
            ],
        );
        $this->calculateService = new Calculate($soapOptions);
    }

    /**
     * Calculate available products for a shipment.
     */
    public function calculateProducts(
        AccountNumber $accountNumber,
        Password $password,
        PostCode $from,
        PostCode $to,
        string $toCityName,
        ShippingType $shippingType,
        float $weight,
        ?float $height = null,
        ?float $length = null,
        ?float $width = null,
        ?\DateTime $shippingDate = null,
    ): ProductList {
        $parameters = new CalculateProducts(
            $accountNumber->getAccountNumber(),
            $password->getPassword(),
            (string) $from->getCountryDelivery()->getCode(),
            $from->getPostCode(),
            (string) $to->getCountryDelivery()->getCode(),
            $to->getPostCode(),
            $toCityName,
            $shippingType->oneLetterCode(),
            (string) $weight,
            $height !== null ? (string) $height : null,
            $length !== null ? (string) $length : null,
            $width !== null ? (string) $width : null,
            $shippingDate !== null ? $shippingDate->format('d/m/Y') : null,
        );

        $result = $this->calculateService->calculateProducts($parameters);

        if ($result === false) {
            $lastError = $this->calculateService->getLastErrorForMethod(methodName: Calculate::class . '::calculateProducts');
            throw new ApiError('Failed to call from calculate service', $lastError);
        }

        $response = $result->getReturn();

        if ($response === null) {
            throw new ApiError('Failed to get result from quickCost service, null response');
        }

        if ($response->getErrorCode() !== 0) {
            $errorMessage = $response->getErrorMessage();
            $errorCode    = $response->getErrorCode();

            throw new CalculateException($errorMessage, $errorCode);
        }

        $factory = new CalculateProductsFactory();

        return $factory->create($response);
    }

    /**
     * Calculate delivery time for a shipment.
     */
    public function calculateDeliveryTime(
        PostCode $from,
        PostCode $to,
        string $toCityName,
        ProductCode $productCode,
        ShippingType $shippingType,
        ServiceCode $serviceCode,
    ): DeliveryTime {
        $parameters = new CalculateDeliveryTime(
            (string) $from->getCountryDelivery()->getCode(),
            $from->getPostCode(),
            (string) $to->getCountryDelivery()->getCode(),
            $to->getPostCode(),
            $toCityName,
            $productCode->getValue(),
            $shippingType->oneLetterCode(),
            $serviceCode->getValue(),
        );

        $result = $this->calculateService->calculateDeliveryTime($parameters);

        if ($result === false) {
            $lastError = $this->calculateService->getLastErrorForMethod(methodName: Calculate::class . '::calculateDeliveryTime');
            throw new ApiError('Failed to call from calculate service', $lastError);
        }

        $response = $result->getReturn();

        if ($response === null) {
            throw new ApiError('Failed to get result from quickCost service, null response');
        }

        if ($response->getErrorCode() !== 0) {
            $errorMessage = $response->getErrorMessage();
            $errorCode    = $response->getErrorCode();

            throw new CalculateException($errorMessage, $errorCode);
        }

        $factory = new CalculateDeliveryTimeFactory();

        return $factory->create($response);
    }
}
