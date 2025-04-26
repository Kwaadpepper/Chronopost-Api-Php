<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\ObjectValues;

use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class AccountNumberTest extends TestCase
{
    public function testCanInstantiateValidAccountNumber(): void
    {
        // GIVEN.
        $accountNumber = '12345678';

        // WHEN.
        new AccountNumber($accountNumber);

        // THEN.
        $this->expectNotToPerformAssertions();
    }
    public function testCannotInstantiateInvalidAccountNumber(): void
    {
        // THEN.
        $this->expectException(\InvalidArgumentException::class);

        // GIVEN.
        $accountNumber = 'INVALID_ACCOUNT_NUMBER';

        // WHEN.
        new AccountNumber($accountNumber);
    }
}
