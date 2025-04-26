<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Factory;

use ChronopostQuickCost\StructType\Assurance;
use ChronopostQuickCost\StructType\Cap;
use ChronopostQuickCost\StructType\Service;
use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\Insurance;
use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\Overload;
use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\Supplement;
use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\SupplementType;
use Kwaadpepper\ChronopostApiPhp\Dto\QuickCostV3;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Money;
use Money\MoneyParser;
use Money\Parser\DecimalMoneyParser;

class QuickCostV3Factory implements Factory
{
    /**
     * Service currency used in responses.
     *
     * @var string
     */
    private static string $currencyCode = 'EUR';

    /**
     * @var \Money\MoneyParser
     */
    private MoneyParser $moneyParser;

    /**
     * @var \Money\Currency
     */
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
     * Create a new instance of QuickCostV3.
     *
     * @param \ChronopostQuickCost\StructType\ResultQuickCostV3 $result
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\QuickCostV3
     * @phpcs:disable Squiz.Commenting.FunctionComment.TypeHintMissing
     */
    public function create($result): QuickCostV3
    {
        $amount      = $result->getAmount();
        $amountTtc   = $result->getAmountTtc();
        $amountTva   = $result->getAmountTva();
        $zone        = $result->getZone();
        $supplements = array_map(
            $this->toSupplement(...),
            $result->getService()
        );
        $insurance   = $this->toInsurance($result->getAssurance());
        $capacity    = $this->toCapacity($result->getCap());


        return new QuickCostV3(
            $this->amountToMoney($amount),
            $this->amountToMoney($amountTtc),
            $this->amountToMoney($amountTva),
            $zone,
            $supplements,
            $insurance,
            $capacity,
        );
    }

    /**
     * Convert a service to a supplement.
     *
     * @param \ChronopostQuickCost\StructType\Service $service
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\Supplement
     */
    private function toSupplement(Service $service): Supplement
    {
        $supplementType = new SupplementType(
            $service->getCodeService(),
            $service->getLabel(),
        );
        $amount         = $service->getAmount();
        $amountTtc      = $service->getAmountTtc();
        $amountTva      = $service->getAmountTva();

        return new Supplement(
            $this->amountToMoney($amount),
            $this->amountToMoney($amountTtc),
            $this->amountToMoney($amountTva),
            $supplementType,
        );
    }

    /**
     * Convert an assurance to an insurance.
     *
     * @param \ChronopostQuickCost\StructType\Assurance $assurance
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\Insurance
     */
    private function toInsurance(Assurance $assurance): Insurance
    {
        $ceil = $assurance->getPlafond();
        $rate = $assurance->getTaux();

        return new Insurance(
            $this->amountToMoney($ceil),
            $rate,
        );
    }

    /**
     * Convert a cap to a capacity.
     *
     * @param \ChronopostQuickCost\StructType\Cap $cat
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\Overload
     */
    private function toCapacity(Cap $cat): Overload
    {
        $plane = $cat->getCapAvion();
        $road  = $cat->getCapRoute();

        return new Overload(
            $plane,
            $road,
        );
    }

    /**
     * Convert a float amount to Money.
     *
     * @param float $amount
     * @return \Money\Money
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
