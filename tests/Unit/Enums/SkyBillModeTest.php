<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\Enums;

use Kwaadpepper\ChronopostApiPhp\Enums\SkyBillMode;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class SkyBillModeTest extends TestCase
{
    /**
     * @return array<string, array{string}>
     */
    public static function validValuesProvider(): array
    {
        return [
            'PDF'     => ['PDF'],
            'PPR'     => ['PPR'],
            'THE'     => ['THE'],
            'THE1015' => ['THE1015'],
            'Z2D'     => ['Z2D'],
            'JSON'    => ['JSON'],
            'SLT'     => ['SLT'],
            'XML'     => ['XML'],
            'XML2D'   => ['XML2D'],
            'THEPSG'  => ['THEPSG'],
            'ZPL300'  => ['ZPL300'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('validValuesProvider')]
    public function testCanCreateFromValidValue(string $value): void
    {
        // WHEN.
        $result = SkyBillMode::tryFrom($value);

        // THEN.
        $this->assertInstanceOf(SkyBillMode::class, $result);
    }

    public function testInvalidValueReturnsNull(): void
    {
        // WHEN.
        $result = SkyBillMode::tryFrom('INVALID');

        // THEN.
        $this->assertNull($result);
    }

    public function testCaseCount(): void
    {
        $this->assertCount(11, SkyBillMode::cases());
    }
}
