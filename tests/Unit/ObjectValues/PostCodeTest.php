<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\ObjectValues;

use Kwaadpepper\ChronopostApiPhp\Enums\CountryDelivery;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class PostCodeTest extends TestCase
{
    public function testCanInstantiateValidPostCode(): void
    {
        // GIVEN.
        $postCode        = '75001';
        $countryDelivery = CountryDelivery::FRANCE;


        // WHEN.
        PostCode::create(
            $postCode,
            $countryDelivery,
        );

        // THEN.
        $this->expectNotToPerformAssertions();
    }

    public function testCannotInstantiateInvalidPostCode(): void
    {
        // THEN.
        $this->expectException(\InvalidArgumentException::class);

        // GIVEN.
        $postCode        = 'INVALID_POST_CODE';
        $countryDelivery = CountryDelivery::FRANCE;

        // WHEN.
        PostCode::create(
            $postCode,
            $countryDelivery,
        );
    }
    public function testCannotInstantiatePostCodeWithAnotherCountry(): void
    {
        // THEN.
        $this->expectException(\InvalidArgumentException::class);

        // GIVEN.
        $postCode        = '75001';
        $countryDelivery = CountryDelivery::GRANDE_BRETAGNE;

        // WHEN.
        PostCode::create(
            $postCode,
            $countryDelivery,
        );
    }
}
