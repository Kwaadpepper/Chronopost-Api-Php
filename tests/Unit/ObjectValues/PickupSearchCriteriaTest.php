<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\ObjectValues;

use Kwaadpepper\ChronopostApiPhp\Enums\CountryForChronopost;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PickupSearchCriteria;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class PickupSearchCriteriaTest extends TestCase
{
    public function testCanInstantiate(): void
    {
        // GIVEN.
        $postCode = new PostCode('75001', CountryForChronopost::FRANCE);

        // WHEN.
        $criteria = new PickupSearchCriteria($postCode, 'Paris');

        // THEN.
        $this->assertSame($postCode, $criteria->getPostCode());
        $this->assertSame('Paris', $criteria->getCity());
    }

    public function testCannotInstantiateEmptyCity(): void
    {
        // THEN.
        $this->expectException(\InvalidArgumentException::class);

        // GIVEN.
        $postCode = new PostCode('75001', CountryForChronopost::FRANCE);

        // WHEN.
        new PickupSearchCriteria($postCode, '');
    }
}
