<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\Enums;

use Kwaadpepper\ChronopostApiPhp\Enums\SlotProductType;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class SlotProductTypeTest extends TestCase
{
    /**
     * @return array<string, array{string}>
     */
    public static function validValuesProvider(): array
    {
        return [
            'RDV'     => ['RDV'],
            'FRESH'   => ['FRESH'],
            'FREEZE'  => ['FREEZE'],
            'AMBIENT' => ['AMBIENT'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('validValuesProvider')]
    public function testCanCreateFromValidValue(string $value): void
    {
        // WHEN.
        $result = SlotProductType::tryFrom($value);

        // THEN.
        $this->assertInstanceOf(SlotProductType::class, $result);
    }
}
