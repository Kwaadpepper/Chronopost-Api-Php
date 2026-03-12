<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\Architecture;

use Kwaadpepper\ChronopostApiPhp\ChronopostApi;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class ChronopostApiRelaySearchMethodNamingTest extends TestCase
{
    public function testGivenChronopostApiClassWhenInspectingRelayMethodNamesThenOnlySearchRelayPointExists(): void
    {
        // GIVEN.
        $className = ChronopostApi::class;

        // WHEN.
        $hasNewMethod = method_exists($className, 'searchRelayPoint');
        $hasLegacyTypoMethod = method_exists($className, 'seachRelayPoint');

        // THEN.
        $this->assertTrue($hasNewMethod, 'ChronopostApi must expose searchRelayPoint().');
        $this->assertFalse($hasLegacyTypoMethod, 'Legacy typo method seachRelayPoint() must be removed.');
    }
}
