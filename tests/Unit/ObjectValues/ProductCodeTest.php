<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\ObjectValues;

use Kwaadpepper\ChronopostApiPhp\ObjectValues\ProductCode;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class ProductCodeTest extends TestCase
{
    public function testCanInstantiateValidProductCode(): void
    {
        // GIVEN.
        $productCode = '2X';

        // WHEN.
        new ProductCode($productCode);

        // THEN.
        $this->expectNotToPerformAssertions();
    }

    public function testCannotInstantiateInvalidProductCode(): void
    {
        // THEN.
        $this->expectException(\InvalidArgumentException::class);

        // GIVEN.
        $productCode = 'INVALID_PRODUCT_CODE';

        // WHEN.
        new ProductCode($productCode);
    }
}
