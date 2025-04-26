<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Services\Cost;

use ChronopostQuickCost\ClassMap;
use ChronopostQuickCost\ServiceType\Quick;
use ChronopostQuickCost\StructType\QuickCostV3 as QuickCostV3Input;
use Kwaadpepper\ChronopostApiPhp\Dto\QuickCostV3;
use Kwaadpepper\ChronopostApiPhp\Enums\ShippingType;
use Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError;
use Kwaadpepper\ChronopostApiPhp\Exceptions\QuickCost\QuickCostException;
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
     *
     * @var \ChronopostQuickCost\ServiceType\Quick
     */
    private Quick $quickService;

    /**
     * Tracking service soap url
     *
     * @var string
     */
    protected static string $serviceUrl = 'https://ws.chronopost.fr/quickcost-cxf/QuickcostServiceWS?wsdl';

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

        $this->quickService = new Quick($soapOptions);
    }

    /**
     * Get quick cost for a shipment.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber $accountNumber The account number.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Password      $password      The password.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode      $from          The sender's postal code.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode      $to            The recipient's postal code.
     * @phpcs:ignore Generic.Files.LineLength.TooLong
     * @param float                                                    $weight        The weight of the shipment in kilograms.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\ProductCode   $productCode   The product code for the shipment.
     * @param \Kwaadpepper\ChronopostApiPhp\Enums\ShippingType         $shippingType  The shipping type.
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\QuickCostV3
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError                     If the API call fails.
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
            (string)$weight,
            $productCode->getValue(),
            $shippingType->value,
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
}
