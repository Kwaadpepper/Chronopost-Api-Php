<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Services\Cost;

use ChronopostQuickCost\ClassMap;
use ChronopostQuickCost\ServiceType\Calculate;
use ChronopostQuickCost\ServiceType\Quick;
use ChronopostQuickCost\StructType\CalculateProducts;
use ChronopostQuickCost\StructType\QuickCostV3 as QuickCostV3Input;
use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\ProductList;
use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\QuickCostV3;
use Kwaadpepper\ChronopostApiPhp\Enums\ShippingType;
use Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError;
use Kwaadpepper\ChronopostApiPhp\Exceptions\QuickCost\QuickCostException;
use Kwaadpepper\ChronopostApiPhp\Factory\CalculateProductsFactory;
use Kwaadpepper\ChronopostApiPhp\Factory\QuickCostV3Factory;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ProductCode;
use WsdlToPhp\PackageBase\SoapClientInterface;

class QuickCostService
{
    /**
     * Soap tracking service
     */
    private Quick $quickService;

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
     * @param  array  $soapOptions  Additional options for the soap client.
     */
    public function __construct(
        array $soapOptions = []
    ) {
        $soapOptions = array_merge(
            $soapOptions,
            [
                SoapClientInterface::WSDL_URL      => static::$serviceUrl,
                SoapClientInterface::WSDL_CLASSMAP => ClassMap::get(),
            ],
        );

        $this->quickService     = new Quick($soapOptions);
        $this->calculateService = new Calculate($soapOptions);
    }

    /**
     * Get quick cost for a shipment.
     *
     * @param  \Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber  $accountNumber  The account number.
     * @param  \Kwaadpepper\ChronopostApiPhp\ObjectValues\Password  $password  The password.
     * @param  \Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode  $from  The sender's postal code.
     * @param  \Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode  $to  The recipient's postal code.
     *
     * @phpcs:ignore Generic.Files.LineLength.TooLong
     *
     * @param  float  $weight  The weight of the shipment in kilograms.
     * @param  \Kwaadpepper\ChronopostApiPhp\ObjectValues\ProductCode  $productCode  The product code for the shipment.
     * @param  \Kwaadpepper\ChronopostApiPhp\Enums\ShippingType  $shippingType  The shipping type.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the API call fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\QuickCost\QuickCostException If the API returns an error.
     */
    public function quickCostV3(
        AccountNumber $accountNumber,
        Password $password,
        PostCode $from,
        PostCode $to,
        float $weight,
        ProductCode $productCode,
        ShippingType $shippingType
    ): QuickCostV3 {
        $parameters = new QuickCostV3Input(
            $accountNumber->getAccountNumber(),
            $password->getPassword(),
            $from->getPostCode(),
            $to->getPostCode(),
            (string) $weight,
            $productCode->getValue(),
            $shippingType->oneLetterCode(),
        );

        $result = $this->quickService->quickCostV3($parameters);
        if ($result === false) {
            $lastError = $this->quickService->getLastErrorForMethod(methodName: 'quickCostV3');
            throw new ApiError('Failed to call from quickCost service', $lastError);
        }

        $response = $result->getReturn();

        if ($response === null) {
            throw new ApiError('Failed to get result from quickCost service, null response');
        }

        if ($response->getErrorCode() !== 0) {
            $errorMessage = $response->getErrorMessage();
            $errorCode    = $response->getErrorCode();

            throw new QuickCostException($errorMessage, $errorCode);
        }

        $factory = new QuickCostV3Factory();

        return $factory->create($response);
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
        ?\DateTime $shippingDate = null
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
            $shippingDate !== null ? $shippingDate->format('Y-m-d') : null
        );

        $result = $this->calculateService->calculateProducts($parameters);

        if ($result === false) {
            $lastError = $this->calculateService->getLastErrorForMethod(methodName: 'calculateProducts');
            throw new ApiError('Failed to call from calculate service', $lastError);
        }

        $response = $result->getReturn();

        if ($response === null) {
            throw new ApiError('Failed to get result from quickCost service, null response');
        }

        if ($response->getErrorCode() !== 0) {
            $errorMessage = $response->getErrorMessage();
            $errorCode    = $response->getErrorCode();

            throw new QuickCostException($errorMessage, $errorCode);
        }

        $factory = new CalculateProductsFactory();

        return $factory->create($response);
    }
}
