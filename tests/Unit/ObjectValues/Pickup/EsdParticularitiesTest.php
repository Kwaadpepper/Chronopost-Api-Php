<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\ObjectValues\Pickup;

use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\EsdParticularities;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class EsdParticularitiesTest extends TestCase
{
    public function testCanInstantiateWithAllFields(): void
    {
        // WHEN.
        $esd = new EsdParticularities(
            feasibilityStudy: true,
            bulkyVolume: false,
            height: 50,
            width: 40,
            length: 60,
            specialInstructions: 'Fragile',
            announcedParcels: '3',
            shipmentCount: 3,
            weight: 12.5,
            volume: '0.12',
        );

        // THEN.
        $this->assertTrue($esd->getFeasibilityStudy());
        $this->assertFalse($esd->getBulkyVolume());
        $this->assertSame(50, $esd->getHeight());
        $this->assertSame(40, $esd->getWidth());
        $this->assertSame(60, $esd->getLength());
        $this->assertSame('Fragile', $esd->getSpecialInstructions());
        $this->assertSame('3', $esd->getAnnouncedParcels());
        $this->assertSame(3, $esd->getShipmentCount());
        $this->assertSame(12.5, $esd->getWeight());
        $this->assertSame('0.12', $esd->getVolume());
    }

    public function testCanInstantiateWithDefaults(): void
    {
        // WHEN.
        $esd = new EsdParticularities();

        // THEN.
        $this->assertNull($esd->getFeasibilityStudy());
        $this->assertNull($esd->getBulkyVolume());
        $this->assertNull($esd->getHeight());
        $this->assertNull($esd->getWeight());
    }
}
