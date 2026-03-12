<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\ObjectValues;

use Kwaadpepper\ChronopostApiPhp\ObjectValues\ParcelDimensions;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class ParcelDimensionsTest extends TestCase
{
    public function testCanInstantiateValidDimensions(): void
    {
        // GIVEN.
        $height = 10.0;
        $length = 20.0;
        $width  = 15.0;

        // WHEN.
        $dims = new ParcelDimensions($height, $length, $width);

        // THEN.
        $this->assertSame(10.0, $dims->getHeight());
        $this->assertSame(20.0, $dims->getLength());
        $this->assertSame(15.0, $dims->getWidth());
    }

    public function testCannotInstantiateZeroHeight(): void
    {
        // THEN.
        $this->expectException(\InvalidArgumentException::class);

        // GIVEN / WHEN.
        new ParcelDimensions(0.0, 20.0, 15.0);
    }

    public function testCannotInstantiateZeroLength(): void
    {
        // THEN.
        $this->expectException(\InvalidArgumentException::class);

        // GIVEN / WHEN.
        new ParcelDimensions(10.0, 0.0, 15.0);
    }

    public function testCannotInstantiateZeroWidth(): void
    {
        // THEN.
        $this->expectException(\InvalidArgumentException::class);

        // GIVEN / WHEN.
        new ParcelDimensions(10.0, 20.0, 0.0);
    }

    public function testCannotInstantiateNegativeHeight(): void
    {
        // THEN.
        $this->expectException(\InvalidArgumentException::class);

        // GIVEN / WHEN.
        new ParcelDimensions(-5.0, 20.0, 15.0);
    }

    public function testCannotInstantiateNegativeLength(): void
    {
        // THEN.
        $this->expectException(\InvalidArgumentException::class);

        // GIVEN / WHEN.
        new ParcelDimensions(10.0, -1.0, 15.0);
    }

    public function testCannotInstantiateNegativeWidth(): void
    {
        // THEN.
        $this->expectException(\InvalidArgumentException::class);

        // GIVEN / WHEN.
        new ParcelDimensions(10.0, 20.0, -3.0);
    }
}
