<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\Enums;

use Kwaadpepper\ChronopostApiPhp\Enums\ChronopostProductCode;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class ChronopostProductCodeTest extends TestCase
{
    /**
     * @return array<string, array{ChronopostProductCode}>
     */
    public static function shop2ShopCodesProvider(): array
    {
        return [
            'CHRONO_RELAIS_13 (5A)'              => [ChronopostProductCode::CHRONO_RELAIS_13],
            'CHRONO_TO_SHOP_DIRECT (5X)'         => [ChronopostProductCode::CHRONO_TO_SHOP_DIRECT],
            'CHRONO_TO_SHOP_DIRECT_PETITPROS (5E)' => [ChronopostProductCode::CHRONO_TO_SHOP_DIRECT_PETITPROS],
            'CHRONO_TO_SHOP_DIRECT_EUROPE (6B)'  => [ChronopostProductCode::CHRONO_TO_SHOP_DIRECT_EUROPE],
        ];
    }

    /**
     * @return array<string, array{ChronopostProductCode}>
     */
    public static function nonShop2ShopCodesProvider(): array
    {
        return [
            'CHRONO_13 (01)'        => [ChronopostProductCode::CHRONO_13],
            'CHRONO_10 (02)'        => [ChronopostProductCode::CHRONO_10],
            'CHRONO_18 (16)'        => [ChronopostProductCode::CHRONO_18],
            'CHRONO_RELAIS (86)'    => [ChronopostProductCode::CHRONO_RELAIS],
            'CHRONO_RELAIS_DOM (4P)' => [ChronopostProductCode::CHRONO_RELAIS_DOM],
            'CHRONO_RELAIS_EUROPE (49)' => [ChronopostProductCode::CHRONO_RELAIS_EUROPE],
        ];
    }

    /**
     * @return array<string, array{ChronopostProductCode}>
     */
    public static function relayDeliveryCodesProvider(): array
    {
        return [
            'CHRONO_RELAIS_13 (5A)'              => [ChronopostProductCode::CHRONO_RELAIS_13],
            'CHRONO_RELAIS_13_SPECIAL (5L)'      => [ChronopostProductCode::CHRONO_RELAIS_13_SPECIAL],
            'CHRONO_RELAIS_EUROPE (49)'          => [ChronopostProductCode::CHRONO_RELAIS_EUROPE],
            'CHRONO_RELAIS (86)'                 => [ChronopostProductCode::CHRONO_RELAIS],
            'CHRONO_RELAIS_AMBIENT (5Q)'         => [ChronopostProductCode::CHRONO_RELAIS_AMBIENT],
            'CHRONO_9_RELAIS (80)'               => [ChronopostProductCode::CHRONO_9_RELAIS],
            'CHRONO_RELAIS_DOM (4P)'             => [ChronopostProductCode::CHRONO_RELAIS_DOM],
            'CHRONO_TO_SHOP_DIRECT_PETITPROS (5E)' => [ChronopostProductCode::CHRONO_TO_SHOP_DIRECT_PETITPROS],
            'CHRONO_TO_SHOP_DIRECT (5X)'         => [ChronopostProductCode::CHRONO_TO_SHOP_DIRECT],
            'CHRONO_TO_SHOP_DIRECT_EUROPE (6B)'  => [ChronopostProductCode::CHRONO_TO_SHOP_DIRECT_EUROPE],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('shop2ShopCodesProvider')]
    public function testIsShop2ShopReturnsTrueForShop2ShopCodes(ChronopostProductCode $code): void
    {
        $this->assertTrue($code->isShop2Shop());
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('nonShop2ShopCodesProvider')]
    public function testIsShop2ShopReturnsFalseForNonShop2ShopCodes(ChronopostProductCode $code): void
    {
        $this->assertFalse($code->isShop2Shop());
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('relayDeliveryCodesProvider')]
    public function testIsRelayDeliveryReturnsTrueForRelayCodes(ChronopostProductCode $code): void
    {
        $this->assertTrue($code->isRelayDelivery());
    }

    public function testIsHomeDeliveryExcludesRelayAndBureau(): void
    {
        $this->assertFalse(ChronopostProductCode::CHRONO_RELAIS->isHomeDelivery());
        $this->assertFalse(ChronopostProductCode::CHRONO_RETRAIT_BUREAU->isHomeDelivery());
        $this->assertTrue(ChronopostProductCode::CHRONO_13->isHomeDelivery());
    }

    public function testTryFromOrUnknownReturnsCaseForValidCode(): void
    {
        $this->assertSame(ChronopostProductCode::CHRONO_13, ChronopostProductCode::tryFromOrUnknown('01'));
        $this->assertSame(ChronopostProductCode::CHRONO_RELAIS_13, ChronopostProductCode::tryFromOrUnknown('5A'));
    }

    public function testTryFromOrUnknownReturnsUnknownForInvalidCode(): void
    {
        $this->assertSame(ChronopostProductCode::UNKNOWN, ChronopostProductCode::tryFromOrUnknown('ZZ'));
        $this->assertSame(ChronopostProductCode::UNKNOWN, ChronopostProductCode::tryFromOrUnknown(null));
        $this->assertSame(ChronopostProductCode::UNKNOWN, ChronopostProductCode::tryFromOrUnknown(''));
    }

    public function testFromCodeReturnsLabelForValidCode(): void
    {
        $this->assertSame('Chrono 13', ChronopostProductCode::fromCode('01'));
    }

    public function testFromCodeReturnsFallbackForInvalidCode(): void
    {
        $this->assertSame('Produit inconnu (ZZ)', ChronopostProductCode::fromCode('ZZ'));
    }
}
