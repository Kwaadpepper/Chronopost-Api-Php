<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\ObjectValues\Pickup;

use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\DpdRecipient;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\DpdRecipientAddress;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\DpdRecipients;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class DpdRecipientsTest extends TestCase
{
    public function testCanInstantiateWithMultipleRecipients(): void
    {
        // GIVEN.
        $r1 = new DpdRecipient(address: new DpdRecipientAddress(city: 'Berlin'));
        $r2 = new DpdRecipient(address: new DpdRecipientAddress(city: 'Madrid'));

        // WHEN.
        $recipients = new DpdRecipients($r1, $r2);

        // THEN.
        $this->assertCount(2, $recipients->getRecipients());
        $this->assertSame($r1, $recipients->getRecipients()[0]);
        $this->assertSame($r2, $recipients->getRecipients()[1]);
    }

    public function testCanInstantiateEmpty(): void
    {
        // WHEN.
        $recipients = new DpdRecipients();

        // THEN.
        $this->assertCount(0, $recipients->getRecipients());
    }
}
