<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\ObjectValues;

use Kwaadpepper\ChronopostApiPhp\Enums\CountryForChronopost;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Address;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class AddressTest extends TestCase
{
    public function testCanInstantiateValidAddress(): void
    {
        // GIVEN.
        $postCode = new PostCode('75001', CountryForChronopost::FRANCE);

        // WHEN.
        $address = new Address('10 Rue de Rivoli', null, 'Paris', $postCode);

        // THEN.
        $this->assertSame('10 Rue de Rivoli', $address->getAddress1());
        $this->assertNull($address->getAddress2());
        $this->assertSame('Paris', $address->getCity());
        $this->assertSame($postCode, $address->getPostCode());
    }

    public function testCanInstantiateWithAddress2(): void
    {
        // GIVEN.
        $postCode = new PostCode('75001', CountryForChronopost::FRANCE);

        // WHEN.
        $address = new Address('10 Rue de Rivoli', 'Batiment A', 'Paris', $postCode);

        // THEN.
        $this->assertSame('Batiment A', $address->getAddress2());
    }

    public function testCannotInstantiateEmptyAddress1(): void
    {
        // THEN.
        $this->expectException(\InvalidArgumentException::class);

        // GIVEN.
        $postCode = new PostCode('75001', CountryForChronopost::FRANCE);

        // WHEN.
        new Address('', null, 'Paris', $postCode);
    }

    public function testCannotInstantiateAddress1TooLong(): void
    {
        // THEN.
        $this->expectException(\InvalidArgumentException::class);

        // GIVEN.
        $postCode = new PostCode('75001', CountryForChronopost::FRANCE);
        $longAddr = str_repeat('a', 39);

        // WHEN.
        new Address($longAddr, null, 'Paris', $postCode);
    }

    public function testCannotInstantiateAddress2TooLong(): void
    {
        // THEN.
        $this->expectException(\InvalidArgumentException::class);

        // GIVEN.
        $postCode = new PostCode('75001', CountryForChronopost::FRANCE);
        $longAddr = str_repeat('a', 39);

        // WHEN.
        new Address('10 Rue de Rivoli', $longAddr, 'Paris', $postCode);
    }

    public function testCannotInstantiateEmptyCity(): void
    {
        // THEN.
        $this->expectException(\InvalidArgumentException::class);

        // GIVEN.
        $postCode = new PostCode('75001', CountryForChronopost::FRANCE);

        // WHEN.
        new Address('10 Rue de Rivoli', null, '', $postCode);
    }

    public function testCannotInstantiateCityTooLong(): void
    {
        // THEN.
        $this->expectException(\InvalidArgumentException::class);

        // GIVEN.
        $postCode = new PostCode('75001', CountryForChronopost::FRANCE);
        $longCity = str_repeat('a', 51);

        // WHEN.
        new Address('10 Rue de Rivoli', null, $longCity, $postCode);
    }

    public function testAddress1MaxLength38IsValid(): void
    {
        // GIVEN.
        $postCode = new PostCode('75001', CountryForChronopost::FRANCE);
        $addr38   = str_repeat('a', 38);

        // WHEN.
        $address = new Address($addr38, null, 'Paris', $postCode);

        // THEN.
        $this->assertSame($addr38, $address->getAddress1());
    }
}
