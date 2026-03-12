<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\ObjectValues;

use Kwaadpepper\ChronopostApiPhp\ObjectValues\Weight;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class WeightTest extends TestCase
{
    public function testCanInstantiateValidWeight(): void
    {
        // GIVEN.
        $kg = 5.0;

        // WHEN.
        $weight = new Weight($kg);

        // THEN.
        $this->assertSame(5.0, $weight->getKg());
    }

    public function testCanInstantiateMinWeight(): void
    {
        // GIVEN.
        $kg = 0.01;

        // WHEN.
        $weight = new Weight($kg);

        // THEN.
        $this->assertSame(0.01, $weight->getKg());
    }

    public function testCanInstantiateMaxWeight(): void
    {
        // GIVEN.
        $kg = 99.0;

        // WHEN.
        $weight = new Weight($kg);

        // THEN.
        $this->assertSame(99.0, $weight->getKg());
    }

    public function testCannotInstantiateWeightBelowMin(): void
    {
        // THEN.
        $this->expectException(\InvalidArgumentException::class);

        // GIVEN / WHEN.
        new Weight(0.0);
    }

    public function testCannotInstantiateWeightAboveMax(): void
    {
        // THEN.
        $this->expectException(\InvalidArgumentException::class);

        // GIVEN / WHEN.
        new Weight(99.01);
    }

    public function testCannotInstantiateNegativeWeight(): void
    {
        // THEN.
        $this->expectException(\InvalidArgumentException::class);

        // GIVEN / WHEN.
        new Weight(-1.0);
    }

    public function testToString(): void
    {
        // GIVEN.
        $weight = new Weight(3.5);

        // WHEN / THEN.
        $this->assertSame('3.5', (string) $weight);
    }
}
