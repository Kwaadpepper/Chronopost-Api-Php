<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Services\Calculate;

use ChronopostQuickCost\ClassMap;
use ChronopostQuickCost\ServiceType\Calculate;
use ChronopostQuickCost\StructType\CalculateDeliveryTime;
use ChronopostQuickCost\StructType\CalculateProducts;
use ChronopostQuickCost\StructType\CalculateProductsV2 as CalculateProductsV2Input;
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
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ShippingEstimateRequest;
use WsdlToPhp\PackageBase\SoapClientInterface;

class CalculateService
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
        #[\SensitiveParameter] private AccountNumber $accountNumber,
        #[\SensitiveParameter] private Password $password,
        array $soapOptions = [],
        ?Calculate $calculateService = null,
    ) {
        if ($calculateService !== null) {
            $this->calculateService = $calculateService;
            return;
        }

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
        ShippingEstimateRequest $request,
    ): ProductList {
        $dimensions = $request->getDimensions();
        $parameters = new CalculateProducts(
            $this->accountNumber->getAccountNumber(),
            $this->password->getPassword(),
            (string) $request->getFrom()->getCountryDelivery()->getCode(),
            $request->getFrom()->getPostCode(),
            (string) $request->getTo()->getCountryDelivery()->getCode(),
            $request->getTo()->getPostCode(),
            $request->getToCityName(),
            $request->getShippingType()->oneLetterCode(),
            (string) $request->getWeight()->getKg(),
            $dimensions !== null ? (string) $dimensions->getHeight() : null,
            $dimensions !== null ? (string) $dimensions->getLength() : null,
            $dimensions !== null ? (string) $dimensions->getWidth() : null,
            $request->getShippingDate() !== null ? $request->getShippingDate()->format('d/m/Y') : null,
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
     * Calculate available products for a shipment (V2, with caller token).
     */
    public function calculateProductsV2(
        string $caller,
        ShippingEstimateRequest $request,
        ?string $nationalite = null,
        ?string $isPart = null,
    ): ProductList {
        $dimensions = $request->getDimensions();
        $parameters = new CalculateProductsV2Input(
            $caller,
            (string) $request->getFrom()->getCountryDelivery()->getCode(),
            $request->getFrom()->getPostCode(),
            (string) $request->getTo()->getCountryDelivery()->getCode(),
            $request->getTo()->getPostCode(),
            $request->getToCityName(),
            $request->getShippingType()->oneLetterCode(),
            (string) $request->getWeight()->getKg(),
            $dimensions !== null ? (string) $dimensions->getHeight() : null,
            $dimensions !== null ? (string) $dimensions->getLength() : null,
            $dimensions !== null ? (string) $dimensions->getWidth() : null,
            $request->getShippingDate() !== null ? $request->getShippingDate()->format('d/m/Y') : null,
            $nationalite,
            $isPart,
        );

        $result = $this->calculateService->calculateProductsV2($parameters);

        if ($result === false) {
            $lastError = $this->calculateService->getLastErrorForMethod(
                methodName: Calculate::class . '::calculateProductsV2',
            );
            throw new ApiError('Failed to call from calculate service', $lastError);
        }

        $response = $result->getReturn();

        if ($response === null) {
            throw new ApiError('Failed to get result from calculateProductsV2 service, null response');
        }

        if ($response->getErrorCode() !== 0) {
            throw new CalculateException($response->getErrorMessage(), $response->getErrorCode());
        }

        return (new CalculateProductsFactory())->create($response);
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
