<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\ObjectValues;

use Kwaadpepper\ChronopostApiPhp\Enums\CountryForChronopost;
use Kwaadpepper\ChronopostApiPhp\Enums\ParcelState;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\DateRange;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\SenderReference;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ServiceCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingSearchCriteria;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class TrackingSearchCriteriaTest extends TestCase
{
    public function testCanInstantiateEmpty(): void
    {
        // WHEN.
        $criteria = new TrackingSearchCriteria();

        // THEN.
        $this->assertNull($criteria->getConsigneesCountry());
        $this->assertNull($criteria->getConsigneesRef());
        $this->assertNull($criteria->getConsigneesPostCode());
        $this->assertNull($criteria->getDepositDateRange());
        $this->assertNull($criteria->getParcelState());
        $this->assertNull($criteria->getSendersRef());
        $this->assertNull($criteria->getServiceCode());
    }

    public function testCanInstantiateWithAllFields(): void
    {
        // GIVEN.
        $country   = CountryForChronopost::FRANCE;
        $ref       = new SenderReference('REF-001');
        $postCode  = new PostCode('75001', CountryForChronopost::FRANCE);
        $dateRange = new DateRange(new \DateTimeImmutable('2024-01-01'), new \DateTimeImmutable('2024-01-31'));
        $state     = ParcelState::DISTRIBUES;
        $senderRef = new SenderReference('SENDER-001');
        $service   = new ServiceCode('086');

        // WHEN.
        $criteria = new TrackingSearchCriteria(
            consigneesCountry: $country,
            consigneesRef: $ref,
            consigneesPostCode: $postCode,
            depositDateRange: $dateRange,
            parcelState: $state,
            sendersRef: $senderRef,
            serviceCode: $service,
        );

        // THEN.
        $this->assertSame($country, $criteria->getConsigneesCountry());
        $this->assertSame($ref, $criteria->getConsigneesRef());
        $this->assertSame($postCode, $criteria->getConsigneesPostCode());
        $this->assertSame($dateRange, $criteria->getDepositDateRange());
        $this->assertSame($state, $criteria->getParcelState());
        $this->assertSame($senderRef, $criteria->getSendersRef());
        $this->assertSame($service, $criteria->getServiceCode());
    }
}
