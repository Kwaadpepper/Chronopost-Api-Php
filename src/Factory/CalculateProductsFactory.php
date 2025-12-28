<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Factory;

use ChronopostQuickCost\StructType\Product as ChronopostProduct;
use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\Product;
use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\ProductList;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Money;
use Money\MoneyParser;
use Money\Parser\DecimalMoneyParser;

class CalculateProductsFactory implements Factory
{
    /**
     * Service currency used in responses.
     */
    private static string $currencyCode = 'EUR';

    private MoneyParser $moneyParser;

    private Currency $currency;

    /**
     * QuickCostV3Factory constructor.
     */
    public function __construct()
    {
        $currencies        = new ISOCurrencies();
        $this->currency    = new Currency(self::$currencyCode);
        $this->moneyParser = new DecimalMoneyParser($currencies);
    }

    /**
     * Create a QuickCostV3 DTO from Chronopost ResultCalculateProducts.
     *
     * @param  \ChronopostQuickCost\StructType\ResultCalculateProducts  $result
     */
    public function create($result): ProductList
    {
        $products = array_map(
            $this->toProduct(...),
            $result->getProductList() ?? []
        );

        return new ProductList($products);
    }

    /**
     * Convert a Chronopost Product to a DTO Product.
     */
    private function toProduct(ChronopostProduct $product): Product
    {
        $amount         = $product->getAmount();
        $amountTtc      = $product->getAmountTtc();
        $amountTva      = $product->getAmountTva();

        return new Product(
            $product->getProductCode(),
            $this->amountToMoney($amount),
            $this->amountToMoney($amountTtc),
            $this->amountToMoney($amountTva)
        );
    }

    /**
     * Convert a float amount to Money.
     */
    private function amountToMoney(float $amount): Money
    {
        $amountWithFloatingPoint = number_format($amount, 4, '.', '');

        return $this->moneyParser->parse(
            $amountWithFloatingPoint,
            $this->currency
        );
    }
}
