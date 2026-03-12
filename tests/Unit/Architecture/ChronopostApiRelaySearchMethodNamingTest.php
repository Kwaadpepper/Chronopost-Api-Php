<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\Architecture;

use Kwaadpepper\ChronopostApiPhp\Facade\RelayFacade;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class ChronopostApiRelaySearchMethodNamingTest extends TestCase
{
    public function testGivenChronopostApiClassWhenInspectingRelayMethodNamesThenOnlySearchRelayPointExists(): void
    {
        // GIVEN.
        $className = RelayFacade::class;

        // WHEN.
        $hasNewMethod        = method_exists($className, 'searchRelayPoint');
        $hasLegacyTypoMethod = method_exists($className, 'seachRelayPoint');

        // THEN.
        $this->assertTrue($hasNewMethod, 'RelayFacade must expose searchRelayPoint().');
        $this->assertFalse($hasLegacyTypoMethod, 'Legacy typo method seachRelayPoint() must be removed.');
    }

    public function testGivenChronopostApiClassWhenInspectingThenPhase6MethodsExist(): void
    {
        $className = RelayFacade::class;

        $this->assertTrue(method_exists($className, 'searchRelayPointByCoordinates'));
        $this->assertTrue(method_exists($className, 'searchRelayPointById'));
        $this->assertTrue(method_exists($className, 'getRelayPointDetail'));
        $this->assertTrue(method_exists($className, 'getInternationalRelayPointDetail'));
    }
}
