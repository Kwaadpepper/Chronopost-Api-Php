<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\Enums;

use Kwaadpepper\ChronopostApiPhp\Enums\SlotType;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class SlotTypeTest extends TestCase
{
    /**
     * @return array<string, array{string}>
     */
    public static function validValuesProvider(): array
    {
        return [
            'DAY'     => ['J'],
            'EVENING' => ['S'],
            'ALL'     => [''],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('validValuesProvider')]
    public function testCanCreateFromValidValue(string $value): void
    {
        // WHEN.
        $result = SlotType::tryFrom($value);

        // THEN.
        $this->assertInstanceOf(SlotType::class, $result);
    }
}
