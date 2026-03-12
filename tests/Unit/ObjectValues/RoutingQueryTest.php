<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\ObjectValues;

use Kwaadpepper\ChronopostApiPhp\Enums\CountryForChronopost;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\RoutingQuery;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class RoutingQueryTest extends TestCase
{
    public function testCanInstantiateWithRequiredFields(): void
    {
        // GIVEN.
        $postCode = new PostCode('75001', CountryForChronopost::FRANCE);

        // WHEN.
        $query = new RoutingQuery('DEPOT01', $postCode);

        // THEN.
        $this->assertSame('DEPOT01', $query->getShipperDepot());
        $this->assertSame($postCode, $query->getDestination());
        $this->assertNull($query->getSocode());
        $this->assertNull($query->getAscode());
    }

    public function testCanInstantiateWithAllFields(): void
    {
        // GIVEN.
        $postCode = new PostCode('75001', CountryForChronopost::FRANCE);

        // WHEN.
        $query = new RoutingQuery('DEPOT01', $postCode, 'SO001', 'AS001');

        // THEN.
        $this->assertSame('SO001', $query->getSocode());
        $this->assertSame('AS001', $query->getAscode());
    }

    public function testCannotInstantiateEmptyDepot(): void
    {
        // THEN.
        $this->expectException(\InvalidArgumentException::class);

        // GIVEN.
        $postCode = new PostCode('75001', CountryForChronopost::FRANCE);

        // WHEN.
        new RoutingQuery('', $postCode);
    }
}
