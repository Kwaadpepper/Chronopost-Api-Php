<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\Enums;

use Kwaadpepper\ChronopostApiPhp\Enums\ModeRetour;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class ModeRetourTest extends TestCase
{
    /**
     * @return array<string, array{int}>
     */
    public static function validValuesProvider(): array
    {
        return [
            'EMAIL_LABEL' => [1],
            'NO_EMAIL'    => [2],
            'SMS_KIOSK'   => [3],
            'SHOP2SHOP'   => [4],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('validValuesProvider')]
    public function testCanCreateFromValidValue(int $value): void
    {
        // WHEN.
        $result = ModeRetour::tryFrom($value);

        // THEN.
        $this->assertInstanceOf(ModeRetour::class, $result);
    }
}
