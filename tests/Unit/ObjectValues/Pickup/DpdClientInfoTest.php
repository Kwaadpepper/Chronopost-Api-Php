<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\ObjectValues\Pickup;

use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\DpdClientInfo;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class DpdClientInfoTest extends TestCase
{
    public function testCanInstantiateWithAllFields(): void
    {
        // WHEN.
        $info = new DpdClientInfo(
            content: 'Electronics',
            currency: 'EUR',
            amount: 150.0,
            clientEsdRef: 'ESD-REF-001',
            service: 'EXPRESS',
        );

        // THEN.
        $this->assertSame('Electronics', $info->getContent());
        $this->assertSame('EUR', $info->getCurrency());
        $this->assertSame(150.0, $info->getAmount());
        $this->assertSame('ESD-REF-001', $info->getClientEsdRef());
        $this->assertSame('EXPRESS', $info->getService());
    }

    public function testCanInstantiateWithDefaults(): void
    {
        // WHEN.
        $info = new DpdClientInfo();

        // THEN.
        $this->assertNull($info->getContent());
        $this->assertNull($info->getCurrency());
        $this->assertNull($info->getAmount());
        $this->assertNull($info->getClientEsdRef());
        $this->assertNull($info->getService());
    }
}
