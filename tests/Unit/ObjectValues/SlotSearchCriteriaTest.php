<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\ObjectValues;

use Kwaadpepper\ChronopostApiPhp\Enums\CountryForChronopost;
use Kwaadpepper\ChronopostApiPhp\Enums\SlotProductType;
use Kwaadpepper\ChronopostApiPhp\Enums\SlotType;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Address;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\DateRange;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\SlotSearchCriteria;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Weight;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class SlotSearchCriteriaTest extends TestCase
{
    public function testCanInstantiateWithRequiredFields(): void
    {
        // GIVEN.
        $postCode  = new PostCode('75001', CountryForChronopost::FRANCE);
        $recipient = new Address('10 Rue de Rivoli', null, 'Paris', $postCode);
        $dateRange = new DateRange(new \DateTimeImmutable('2024-06-01'), new \DateTimeImmutable('2024-06-07'));

        // WHEN.
        $criteria = new SlotSearchCriteria(
            SlotProductType::RDV,
            $recipient,
            $dateRange,
        );

        // THEN.
        $this->assertSame(SlotProductType::RDV, $criteria->getProductType());
        $this->assertSame($recipient, $criteria->getRecipientAddress());
        $this->assertSame($dateRange, $criteria->getDateRange());
        $this->assertNull($criteria->getShipperAddress());
        $this->assertNull($criteria->getWeight());
        $this->assertNull($criteria->getSlotType());
    }

    public function testCanInstantiateWithAllFields(): void
    {
        // GIVEN.
        $postCode  = new PostCode('75001', CountryForChronopost::FRANCE);
        $recipient = new Address('10 Rue de Rivoli', null, 'Paris', $postCode);
        $shipper   = new Address('5 Avenue des Champs', null, 'Paris', $postCode);
        $dateRange = new DateRange(new \DateTimeImmutable('2024-06-01'), new \DateTimeImmutable('2024-06-07'));
        $weight    = new Weight(2.5);

        // WHEN.
        $criteria = new SlotSearchCriteria(
            SlotProductType::FRESH,
            $recipient,
            $dateRange,
            $shipper,
            $weight,
            SlotType::EVENING,
        );

        // THEN.
        $this->assertSame($shipper, $criteria->getShipperAddress());
        $this->assertSame($weight, $criteria->getWeight());
        $this->assertSame(SlotType::EVENING, $criteria->getSlotType());
    }
}
