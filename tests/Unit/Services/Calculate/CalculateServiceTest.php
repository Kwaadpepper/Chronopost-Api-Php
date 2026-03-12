<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\Services\Calculate;

use ChronopostQuickCost\ServiceType\Calculate;
use ChronopostQuickCost\StructType\CalculateProductsResponse;
use ChronopostQuickCost\StructType\CalculateProductsV2Response;
use ChronopostQuickCost\StructType\Product;
use ChronopostQuickCost\StructType\ResultCalculateProducts;
use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\ProductList;
use Kwaadpepper\ChronopostApiPhp\Enums\CountryForChronopost;
use Kwaadpepper\ChronopostApiPhp\Enums\ShippingType;
use Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError;
use Kwaadpepper\ChronopostApiPhp\Exceptions\Calculate\CalculateException;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode;
use Kwaadpepper\ChronopostApiPhp\Services\Calculate\CalculateService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class CalculateServiceTest extends TestCase
{
    private Calculate&MockObject $calculateMock;

    private CalculateService $service;

    private AccountNumber $accountNumber;

    private Password $password;

    private PostCode $from;

    private PostCode $to;

    protected function setUp(): void
    {
        $this->calculateMock = $this->createMock(Calculate::class);
        $this->accountNumber = new AccountNumber('19869502');
        $this->password = new Password('255562');
        $this->from = new PostCode('75001', CountryForChronopost::FRANCE);
        $this->to = new PostCode('69001', CountryForChronopost::FRANCE);

        $this->service = new CalculateService(
            calculateService: $this->calculateMock,
        );
    }

    /**
     * @param  string ...$codes
     * @return Product[]
     */
    private function createProducts(string ...$codes): array
    {
        return array_map(static function (string $code): Product {
            $p = new Product();
            $p->setProductCode($code);

            return $p;
        }, $codes);
    }

    /**
     * @param  Product[]|null $products
     */
    private function createSoapResult(
        int $errorCode = 0,
        string $errorMessage = '',
        ?array $products = null,
    ): ResultCalculateProducts {
        $result = new ResultCalculateProducts($products);
        $result->setErrorCode($errorCode);
        $result->setErrorMessage($errorMessage);

        return $result;
    }

    public function testGivenValidParamsWhenCalculateProductsThenReturnsProductList(): void
    {
        $products = $this->createProducts('01', '86');
        $soapResult = $this->createSoapResult(products: $products);
        $soapResponse = new CalculateProductsResponse(return: $soapResult);

        $this->calculateMock
            ->method('calculateProducts')
            ->willReturn($soapResponse);

        $result = $this->service->calculateProducts(
            $this->accountNumber,
            $this->password,
            $this->from,
            $this->to,
            'Lyon',
            ShippingType::MERCHANDISE,
            2.5,
        );

        self::assertInstanceOf(ProductList::class, $result);
        self::assertCount(2, $result->products);
        self::assertSame('01', $result->products[0]->originalCode);
        self::assertSame('86', $result->products[1]->originalCode);
    }

    public function testGivenApiErrorWhenCalculateProductsThenThrowsApiError(): void
    {
        $this->calculateMock
            ->method('calculateProducts')
            ->willReturn(false);
        $this->calculateMock
            ->method('getLastErrorForMethod')
            ->willReturn(new \SoapFault('Server', 'Connection failed'));

        $this->expectException(ApiError::class);
        $this->service->calculateProducts(
            $this->accountNumber,
            $this->password,
            $this->from,
            $this->to,
            'Lyon',
            ShippingType::MERCHANDISE,
            2.5,
        );
    }

    public function testGivenErrorCodeWhenCalculateProductsThenThrowsCalculateException(): void
    {
        $soapResult = $this->createSoapResult(errorCode: 1, errorMessage: 'Invalid parameters');
        $soapResponse = new CalculateProductsResponse(return: $soapResult);

        $this->calculateMock
            ->method('calculateProducts')
            ->willReturn($soapResponse);

        $this->expectException(CalculateException::class);
        $this->service->calculateProducts(
            $this->accountNumber,
            $this->password,
            $this->from,
            $this->to,
            'Lyon',
            ShippingType::MERCHANDISE,
            2.5,
        );
    }

    public function testGivenValidParamsWhenCalculateProductsV2ThenReturnsProductList(): void
    {
        $products = $this->createProducts('01', '86');
        $soapResult = $this->createSoapResult(products: $products);
        $soapResponse = new CalculateProductsV2Response(return: $soapResult);

        $this->calculateMock
            ->method('calculateProductsV2')
            ->willReturn($soapResponse);

        $result = $this->service->calculateProductsV2(
            'MY_CALLER_TOKEN',
            $this->from,
            $this->to,
            'Lyon',
            ShippingType::MERCHANDISE,
            2.5,
        );

        self::assertInstanceOf(ProductList::class, $result);
        self::assertCount(2, $result->products);
        self::assertSame('01', $result->products[0]->originalCode);
        self::assertSame('86', $result->products[1]->originalCode);
    }

    public function testGivenApiErrorWhenCalculateProductsV2ThenThrowsApiError(): void
    {
        $this->calculateMock
            ->method('calculateProductsV2')
            ->willReturn(false);
        $this->calculateMock
            ->method('getLastErrorForMethod')
            ->willReturn(new \SoapFault('Server', 'Connection failed'));

        $this->expectException(ApiError::class);
        $this->service->calculateProductsV2(
            'MY_CALLER_TOKEN',
            $this->from,
            $this->to,
            'Lyon',
            ShippingType::MERCHANDISE,
            2.5,
        );
    }

    public function testGivenErrorCodeWhenCalculateProductsV2ThenThrowsCalculateException(): void
    {
        $soapResult = $this->createSoapResult(errorCode: 2, errorMessage: 'Route not available');
        $soapResponse = new CalculateProductsV2Response(return: $soapResult);

        $this->calculateMock
            ->method('calculateProductsV2')
            ->willReturn($soapResponse);

        $this->expectException(CalculateException::class);
        $this->service->calculateProductsV2(
            'MY_CALLER_TOKEN',
            $this->from,
            $this->to,
            'Lyon',
            ShippingType::MERCHANDISE,
            2.5,
        );
    }

    public function testGivenOptionalParamsWhenCalculateProductsV2ThenPassesThemThrough(): void
    {
        $products = $this->createProducts('44');
        $soapResult = $this->createSoapResult(products: $products);
        $soapResponse = new CalculateProductsV2Response(return: $soapResult);

        $this->calculateMock
            ->method('calculateProductsV2')
            ->willReturn($soapResponse);

        $result = $this->service->calculateProductsV2(
            'MY_CALLER_TOKEN',
            $this->from,
            $this->to,
            'Lyon',
            ShippingType::MERCHANDISE,
            5.0,
            height: 30.0,
            length: 40.0,
            width: 20.0,
            shippingDate: new \DateTime('2026-03-15'),
            nationalite: 'FR',
            isPart: '1',
        );

        self::assertInstanceOf(ProductList::class, $result);
        self::assertCount(1, $result->products);
        self::assertSame('44', $result->products[0]->originalCode);
    }
}
