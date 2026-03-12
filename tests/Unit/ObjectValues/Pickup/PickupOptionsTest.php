<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\ObjectValues\Pickup;

use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\PickupOptions;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class PickupOptionsTest extends TestCase
{
    public function testCanInstantiateWithAllFields(): void
    {
        // WHEN.
        $opts = new PickupOptions(
            notifyOnCompletion: true,
            atThirdParty: false,
            sendLtByEmail: true,
            ltPrintedByChronopost: false,
        );

        // THEN.
        $this->assertTrue($opts->getNotifyOnCompletion());
        $this->assertFalse($opts->getAtThirdParty());
        $this->assertTrue($opts->getSendLtByEmail());
        $this->assertFalse($opts->getLtPrintedByChronopost());
    }

    public function testCanInstantiateWithDefaults(): void
    {
        // WHEN.
        $opts = new PickupOptions();

        // THEN.
        $this->assertNull($opts->getNotifyOnCompletion());
        $this->assertNull($opts->getAtThirdParty());
        $this->assertNull($opts->getSendLtByEmail());
        $this->assertNull($opts->getLtPrintedByChronopost());
    }
}
