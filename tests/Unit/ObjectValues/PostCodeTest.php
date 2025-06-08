<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\ObjectValues;

use Kwaadpepper\ChronopostApiPhp\Enums\CountryForChronopost;
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
        $countryDelivery = CountryForChronopost::FRANCE;


        // WHEN.
        new PostCode(
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
        $countryDelivery = CountryForChronopost::FRANCE;

        // WHEN.
        new PostCode(
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
        $countryDelivery = CountryForChronopost::GRANDE_BRETAGNE;

        // WHEN.
        new PostCode(
            $postCode,
            $countryDelivery,
        );
    }
}
