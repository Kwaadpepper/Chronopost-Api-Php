<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\Enums;

use Kwaadpepper\ChronopostApiPhp\Enums\SkyBillWithReservation;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class SkyBillWithReservationTest extends TestCase
{
    /**
     * @return array<string, array{int}>
     */
    public static function validValuesProvider(): array
    {
        return [
            'DEFAULT_NO_RESERVATION'      => [0],
            'WITH_RESERVATION'            => [1],
            'WITH_RESERVATION_AND_FORMAT' => [2],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('validValuesProvider')]
    public function testCanCreateFromValidValue(int $value): void
    {
        // WHEN.
        $result = SkyBillWithReservation::tryFrom($value);

        // THEN.
        $this->assertInstanceOf(SkyBillWithReservation::class, $result);
    }

    public function testInvalidValueReturnsNull(): void
    {
        // WHEN.
        $result = SkyBillWithReservation::tryFrom(99);

        // THEN.
        $this->assertNull($result);
    }

    public function testCaseCount(): void
    {
        $this->assertCount(3, SkyBillWithReservation::cases());
    }
}
