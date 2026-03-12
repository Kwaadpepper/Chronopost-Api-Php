<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\ObjectValues\Pickup;

use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\PickupHeader;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class PickupHeaderTest extends TestCase
{
    public function testCanInstantiateWithAllFields(): void
    {
        // GIVEN.
        $accountNumber = 19869502;
        $idEmit        = 'EMIT001';
        $identWebPro   = 'WEBPRO01';
        $subAccount    = 42;

        // WHEN.
        $header = new PickupHeader($accountNumber, $idEmit, $identWebPro, $subAccount);

        // THEN.
        $this->assertSame($accountNumber, $header->getAccountNumber());
        $this->assertSame($idEmit, $header->getIdEmit());
        $this->assertSame($identWebPro, $header->getIdentWebPro());
        $this->assertSame($subAccount, $header->getSubAccount());
    }

    public function testCanInstantiateWithDefaults(): void
    {
        // WHEN.
        $header = new PickupHeader();

        // THEN.
        $this->assertNull($header->getAccountNumber());
        $this->assertNull($header->getIdEmit());
        $this->assertNull($header->getIdentWebPro());
        $this->assertNull($header->getSubAccount());
    }

    public function testCanInstantiateWithPartialFields(): void
    {
        // WHEN.
        $header = new PickupHeader(accountNumber: 12345678);

        // THEN.
        $this->assertSame(12345678, $header->getAccountNumber());
        $this->assertNull($header->getIdEmit());
    }
}
