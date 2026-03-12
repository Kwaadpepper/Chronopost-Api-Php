<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Services\Cost;

use ChronopostQuickCost\ClassMap;
use ChronopostQuickCost\ServiceType\Get;
use ChronopostQuickCost\ServiceType\Quick;
use ChronopostQuickCost\StructType\GetProducts as GetProductsInput;
use ChronopostQuickCost\StructType\QuickCostV3 as QuickCostV3Input;
use Kwaadpepper\ChronopostApiPhp\Contracts\QuickCostServiceInterface;
use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\ProductCatalog;
use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\QuickCostV3;
use Kwaadpepper\ChronopostApiPhp\Enums\ShippingType;
use Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError;
use Kwaadpepper\ChronopostApiPhp\Exceptions\QuickCost\QuickCostException;
use Kwaadpepper\ChronopostApiPhp\Factory\GetProductsFactory;
use Kwaadpepper\ChronopostApiPhp\Factory\QuickCostV3Factory;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ProductCode;
use WsdlToPhp\PackageBase\SoapClientInterface;

class QuickCostService implements QuickCostServiceInterface
{
    /**
     * Soap tracking service
     */
    private Quick $quickService;

    private Get $getService;

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
        ?Quick $quickService = null,
        ?Get $getService = null,
    ) {
        if ($quickService !== null && $getService !== null) {
            $this->quickService = $quickService;
            $this->getService = $getService;
            return;
        }

        $soapOptions = array_merge(
            $soapOptions,
            [
                SoapClientInterface::WSDL_URL      => static::$serviceUrl,
                SoapClientInterface::WSDL_CLASSMAP => ClassMap::get(),
            ],
        );

        $this->quickService = $quickService ?? new Quick($soapOptions);
        $this->getService = $getService ?? new Get($soapOptions);
    }

    /**
     * Get quick cost for a shipment.
     *
     * @param  \Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber $accountNumber The account number.
     * @param  \Kwaadpepper\ChronopostApiPhp\ObjectValues\Password      $password      The password.
     * @param  \Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode      $from          The sender's postal code.
     * @param  \Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode      $to            The recipient's postal code.
     *
     * @phpcs:ignore Generic.Files.LineLength.TooLong
     *
     * @param  float                                                    $weight        The weight of the shipment in kilograms.
     * @param  \Kwaadpepper\ChronopostApiPhp\ObjectValues\ProductCode   $productCode   The product code for the shipment.
     * @param  \Kwaadpepper\ChronopostApiPhp\Enums\ShippingType         $shippingType  The shipping type.
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
        ShippingType $shippingType,
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
            $lastError = $this->quickService->getLastErrorForMethod(methodName: Quick::class . '::quickCostV3');
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
     * Get available products for a route.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError If the API call fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\QuickCost\QuickCostException If the API returns an error.
     */
    public function getProducts(
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
    ): ProductCatalog {
        $parameters = new GetProductsInput(
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

        $result = $this->getService->getProducts($parameters);

        if ($result === false) {
            $lastError = $this->getService->getLastErrorForMethod(methodName: Get::class . '::getProducts');
            throw new ApiError('Failed to call from getProducts service', $lastError);
        }

        $response = $result->getReturn();

        if ($response === null) {
            throw new ApiError('Failed to get result from getProducts service, null response');
        }

        if ($response->getErrorCode() !== 0) {
            throw new QuickCostException($response->getErrorMessage(), $response->getErrorCode());
        }

        return (new GetProductsFactory())->create($response);
    }
}
