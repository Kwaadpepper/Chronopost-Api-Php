<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\ObjectValues\Pickup;

use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\DpdClientInfo;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\DpdParticularities;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\DpdRecipient;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\DpdRecipientAddress;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class DpdRecipientTest extends TestCase
{
    public function testCanInstantiateWithAllFields(): void
    {
        // GIVEN.
        $address         = new DpdRecipientAddress(address: '1 Berliner Str', city: 'Berlin');
        $clientInfo      = new DpdClientInfo(content: 'Books', currency: 'EUR');
        $particularities = new DpdParticularities(weight: 5.0);

        // WHEN.
        $recipient = new DpdRecipient(
            address: $address,
            clientInfo: $clientInfo,
            particularities: $particularities,
            insuredValue: 250.0,
        );

        // THEN.
        $this->assertSame($address, $recipient->getAddress());
        $this->assertSame($clientInfo, $recipient->getClientInfo());
        $this->assertSame($particularities, $recipient->getParticularities());
        $this->assertSame(250.0, $recipient->getInsuredValue());
    }

    public function testCanInstantiateWithDefaults(): void
    {
        // WHEN.
        $recipient = new DpdRecipient();

        // THEN.
        $this->assertNull($recipient->getAddress());
        $this->assertNull($recipient->getClientInfo());
        $this->assertNull($recipient->getParticularities());
        $this->assertNull($recipient->getInsuredValue());
    }
}
