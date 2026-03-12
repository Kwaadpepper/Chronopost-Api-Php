<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\ObjectValues;

use Kwaadpepper\ChronopostApiPhp\Enums\CountryForChronopost;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\GeocodingAddress;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class GeocodingAddressTest extends TestCase
{
    public function testCanInstantiateWithRequiredFields(): void
    {
        // GIVEN.
        $postCode = new PostCode('75001', CountryForChronopost::FRANCE);

        // WHEN.
        $address = new GeocodingAddress('10 Rue de Rivoli', $postCode);

        // THEN.
        $this->assertSame('10 Rue de Rivoli', $address->getAddress1());
        $this->assertSame($postCode, $address->getPostCode());
        $this->assertNull($address->getAddress2());
    }

    public function testCanInstantiateWithAddress2(): void
    {
        // GIVEN.
        $postCode = new PostCode('75001', CountryForChronopost::FRANCE);

        // WHEN.
        $address = new GeocodingAddress('10 Rue de Rivoli', $postCode, 'Bat A');

        // THEN.
        $this->assertSame('Bat A', $address->getAddress2());
    }

    public function testCannotInstantiateEmptyAddress1(): void
    {
        // THEN.
        $this->expectException(\InvalidArgumentException::class);

        // GIVEN.
        $postCode = new PostCode('75001', CountryForChronopost::FRANCE);

        // WHEN.
        new GeocodingAddress('', $postCode);
    }

    public function testCannotInstantiateEmptyStringAddress2(): void
    {
        // THEN.
        $this->expectException(\InvalidArgumentException::class);

        // GIVEN.
        $postCode = new PostCode('75001', CountryForChronopost::FRANCE);

        // WHEN.
        new GeocodingAddress('10 Rue de Rivoli', $postCode, '');
    }
}
