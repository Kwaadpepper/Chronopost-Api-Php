<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\Enums;

use Kwaadpepper\ChronopostApiPhp\Enums\SkyBillOutputMode;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class SkyBillOutputModeTest extends TestCase
{
    /**
     * @return array<string, array{int}>
     */
    public static function validValuesProvider(): array
    {
        return [
            'SHIPPER_MAIL_SENDING'         => [1],
            'NO_MAIL_SENDING'              => [2],
            'POST_OFFICE_AND_SMS_PRINTABLE' => [3],
            'SHOP2SHOP_EMAIL'              => [4],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('validValuesProvider')]
    public function testCanCreateFromValidValue(int $value): void
    {
        // WHEN.
        $result = SkyBillOutputMode::tryFrom($value);

        // THEN.
        $this->assertInstanceOf(SkyBillOutputMode::class, $result);
    }

    public function testInvalidValueReturnsNull(): void
    {
        // WHEN.
        $result = SkyBillOutputMode::tryFrom(99);

        // THEN.
        $this->assertNull($result);
    }

    public function testCaseCount(): void
    {
        $this->assertCount(4, SkyBillOutputMode::cases());
    }
}
