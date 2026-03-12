<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\Enums;

use Kwaadpepper\ChronopostApiPhp\Enums\ParcelState;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class ParcelStateTest extends TestCase
{
    /**
     * @return array<string, array{string}>
     */
    public static function validValuesProvider(): array
    {
        return [
            'ANY'            => ['ANY'],
            'NONDISTRIBUES'  => ['NONDISTRIBUES'],
            'DISTRIBUES'     => ['DISTRIBUES'],
            'INTERNATIONAL'  => ['INTERNATIONAL'],
            'INSTANCE'       => ['INSTANCE'],
            'INCIDENT'       => ['INCIDENT'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('validValuesProvider')]
    public function testCanCreateFromValidValue(string $value): void
    {
        // WHEN.
        $result = ParcelState::tryFrom($value);

        // THEN.
        $this->assertInstanceOf(ParcelState::class, $result);
    }
}
