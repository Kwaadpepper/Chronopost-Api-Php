<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\Services\Cost;

use ChronopostQuickCost\ServiceType\Get;
use ChronopostQuickCost\ServiceType\Quick;
use ChronopostQuickCost\StructType\Assurance;
use ChronopostQuickCost\StructType\Cap;
use ChronopostQuickCost\StructType\GetProductsResponse;
use ChronopostQuickCost\StructType\ProductDesc;
use ChronopostQuickCost\StructType\QuickCostV3Response;
use ChronopostQuickCost\StructType\ResultGetProducts;
use ChronopostQuickCost\StructType\ResultQuickCostV3;
use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\ProductCatalog;
use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\QuickCostV3;
use Kwaadpepper\ChronopostApiPhp\Enums\CountryForChronopost;
use Kwaadpepper\ChronopostApiPhp\Enums\ShippingType;
use Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError;
use Kwaadpepper\ChronopostApiPhp\Exceptions\QuickCost\QuickCostException;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ProductCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ShippingEstimateRequest;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Weight;
use Kwaadpepper\ChronopostApiPhp\Services\Cost\QuickCostService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class QuickCostServiceTest extends TestCase
{
    private Quick&MockObject $quickMock;

    private Get&MockObject $getMock;

    private QuickCostService $service;

    private AccountNumber $accountNumber;

    private Password $password;

    private PostCode $from;

    private PostCode $to;

    protected function setUp(): void
    {
        $this->quickMock = $this->createMock(Quick::class);
        $this->getMock = $this->createMock(Get::class);
        $this->accountNumber = new AccountNumber('19869502');
        $this->password = new Password('255562');
        $this->from = new PostCode('75001', CountryForChronopost::FRANCE);
        $this->to = new PostCode('69001', CountryForChronopost::FRANCE);

        $this->service = new QuickCostService(
            accountNumber: $this->accountNumber,
            password: $this->password,
            quickService: $this->quickMock,
            getService: $this->getMock,
        );
    }

    private function createQuickCostV3Result(): ResultQuickCostV3
    {
        $result = new ResultQuickCostV3();
        $result->setAmount(12.50);
        $result->setAmountTTC(15.0);
        $result->setAmountTVA(2.50);
        $result->setErrorCode(0);
        $result->setErrorMessage('');
        $result->setZone('FR');
        $result->setAssurance(new Assurance(1000.0, 0.5));
        $result->setCap(new Cap(4.5, 3.2));

        return $result;
    }

    /**
     * @param  ProductDesc[]|null $productList
     */
    private function createGetProductsResult(
        int $errorCode = 0,
        string $errorMessage = '',
        ?array $productList = null,
    ): ResultGetProducts {
        $result = new ResultGetProducts($productList);
        $result->setErrorCode($errorCode);
        $result->setErrorMessage($errorMessage);

        return $result;
    }

    public function testGivenValidParamsWhenQuickCostV3ThenReturnsResult(): void
    {
        $soapResult = $this->createQuickCostV3Result();
        $soapResponse = new QuickCostV3Response(return: $soapResult);

        $this->quickMock
            ->method('quickCostV3')
            ->willReturn($soapResponse);

        $result = $this->service->quickCostV3(
            $this->from,
            $this->to,
            2.5,
            new ProductCode('01'),
            ShippingType::MERCHANDISE,
        );

        self::assertInstanceOf(QuickCostV3::class, $result);
        self::assertSame('FR', $result->zone);
    }

    public function testGivenApiErrorWhenQuickCostV3ThenThrowsApiError(): void
    {
        $this->quickMock
            ->method('quickCostV3')
            ->willReturn(false);
        $this->quickMock
            ->method('getLastErrorForMethod')
            ->willReturn(new \SoapFault('Server', 'Connection failed'));

        $this->expectException(ApiError::class);
        $this->service->quickCostV3(
            $this->from,
            $this->to,
            2.5,
            new ProductCode('01'),
            ShippingType::MERCHANDISE,
        );
    }

    public function testGivenValidParamsWhenGetProductsThenReturnsCatalog(): void
    {
        $desc1 = new ProductDesc();
        $desc1->setProductCode('01');
        $desc2 = new ProductDesc();
        $desc2->setProductCode('86');
        $desc3 = new ProductDesc();
        $desc3->setProductCode('44');

        $soapResult = $this->createGetProductsResult(productList: [$desc1, $desc2, $desc3]);
        $soapResponse = new GetProductsResponse(return: $soapResult);

        $this->getMock
            ->method('getProducts')
            ->willReturn($soapResponse);

        $result = $this->service->getProducts(
            new ShippingEstimateRequest($this->from, $this->to, 'Lyon', ShippingType::MERCHANDISE, new Weight(2.5)),
        );

        self::assertInstanceOf(ProductCatalog::class, $result);
        self::assertCount(3, $result->products);
        self::assertSame('01', $result->products[0]->originalCode);
        self::assertSame('86', $result->products[1]->originalCode);
        self::assertSame('44', $result->products[2]->originalCode);
    }

    public function testGivenApiErrorWhenGetProductsThenThrowsApiError(): void
    {
        $this->getMock
            ->method('getProducts')
            ->willReturn(false);
        $this->getMock
            ->method('getLastErrorForMethod')
            ->willReturn(new \SoapFault('Server', 'Connection failed'));

        $this->expectException(ApiError::class);
        $this->service->getProducts(
            new ShippingEstimateRequest($this->from, $this->to, 'Lyon', ShippingType::MERCHANDISE, new Weight(2.5)),
        );
    }

    public function testGivenErrorCodeWhenGetProductsThenThrowsQuickCostException(): void
    {
        $soapResult = $this->createGetProductsResult(errorCode: 3, errorMessage: 'Account not authorized');
        $soapResponse = new GetProductsResponse(return: $soapResult);

        $this->getMock
            ->method('getProducts')
            ->willReturn($soapResponse);

        $this->expectException(QuickCostException::class);
        $this->service->getProducts(
            new ShippingEstimateRequest($this->from, $this->to, 'Lyon', ShippingType::MERCHANDISE, new Weight(2.5)),
        );
    }

    public function testGivenEmptyResultWhenGetProductsThenReturnsEmptyCatalog(): void
    {
        $soapResult = $this->createGetProductsResult();
        $soapResponse = new GetProductsResponse(return: $soapResult);

        $this->getMock
            ->method('getProducts')
            ->willReturn($soapResponse);

        $result = $this->service->getProducts(
            new ShippingEstimateRequest($this->from, $this->to, 'Lyon', ShippingType::MERCHANDISE, new Weight(2.5)),
        );

        self::assertInstanceOf(ProductCatalog::class, $result);
        self::assertCount(0, $result->products);
    }
}
