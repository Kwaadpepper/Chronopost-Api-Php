<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\ObjectValues;

use Kwaadpepper\ChronopostApiPhp\Enums\CountryForChronopost;
use Kwaadpepper\ChronopostApiPhp\Enums\ShippingType;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ParcelDimensions;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ShippingEstimateRequest;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Weight;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class ShippingEstimateRequestTest extends TestCase
{
    public function testCanInstantiateWithRequiredFields(): void
    {
        // GIVEN.
        $from   = new PostCode('75001', CountryForChronopost::FRANCE);
        $to     = new PostCode('69001', CountryForChronopost::FRANCE);
        $weight = new Weight(2.5);

        // WHEN.
        $request = new ShippingEstimateRequest(
            $from,
            $to,
            'Lyon',
            ShippingType::MERCHANDISE,
            $weight,
        );

        // THEN.
        $this->assertSame($from, $request->getFrom());
        $this->assertSame($to, $request->getTo());
        $this->assertSame('Lyon', $request->getToCityName());
        $this->assertSame(ShippingType::MERCHANDISE, $request->getShippingType());
        $this->assertSame($weight, $request->getWeight());
        $this->assertNull($request->getDimensions());
        $this->assertNull($request->getShippingDate());
    }

    public function testCanInstantiateWithAllFields(): void
    {
        // GIVEN.
        $from = new PostCode('75001', CountryForChronopost::FRANCE);
        $to   = new PostCode('69001', CountryForChronopost::FRANCE);
        $dims = new ParcelDimensions(10.0, 20.0, 15.0);
        $date = new \DateTimeImmutable('2024-06-15');

        // WHEN.
        $request = new ShippingEstimateRequest(
            $from,
            $to,
            'Lyon',
            ShippingType::DOCUMENTS,
            new Weight(1.0),
            $dims,
            $date,
        );

        // THEN.
        $this->assertSame($dims, $request->getDimensions());
        $this->assertSame($date, $request->getShippingDate());
    }

    public function testCannotInstantiateEmptyCityName(): void
    {
        // THEN.
        $this->expectException(\InvalidArgumentException::class);

        // GIVEN.
        $from = new PostCode('75001', CountryForChronopost::FRANCE);
        $to   = new PostCode('69001', CountryForChronopost::FRANCE);

        // WHEN.
        new ShippingEstimateRequest(
            $from,
            $to,
            '',
            ShippingType::MERCHANDISE,
            new Weight(1.0),
        );
    }
}
