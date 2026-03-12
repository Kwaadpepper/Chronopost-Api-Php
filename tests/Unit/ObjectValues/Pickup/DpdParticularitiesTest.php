<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\ObjectValues\Pickup;

use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\DpdParticularities;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class DpdParticularitiesTest extends TestCase
{
    public function testCanInstantiateWithAllFields(): void
    {
        // WHEN.
        $p = new DpdParticularities(
            height: 30.0,
            specialInstructions: 'Fragile — handle with care',
            width: 20.0,
            length: 40.0,
            shipmentCount: 2,
            weight: 7.5,
        );

        // THEN.
        $this->assertSame(30.0, $p->getHeight());
        $this->assertSame('Fragile — handle with care', $p->getSpecialInstructions());
        $this->assertSame(20.0, $p->getWidth());
        $this->assertSame(40.0, $p->getLength());
        $this->assertSame(2, $p->getShipmentCount());
        $this->assertSame(7.5, $p->getWeight());
    }

    public function testCanInstantiateWithDefaults(): void
    {
        // WHEN.
        $p = new DpdParticularities();

        // THEN.
        $this->assertNull($p->getHeight());
        $this->assertNull($p->getSpecialInstructions());
        $this->assertNull($p->getWidth());
        $this->assertNull($p->getLength());
        $this->assertNull($p->getShipmentCount());
        $this->assertNull($p->getWeight());
    }
}
