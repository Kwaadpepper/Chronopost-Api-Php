<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\ObjectValues;

use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class PasswordTest extends TestCase
{
    public function testCanInstantiateValidPassword(): void
    {
        // GIVEN.
        $password = '123456';

        // WHEN.
        new Password($password);

        // THEN.
        $this->expectNotToPerformAssertions();
    }
    public function testCannotInstantiateInvalidPassword(): void
    {
        // THEN.
        $this->expectException(\InvalidArgumentException::class);

        // GIVEN.
        $password = 'INVALID_PASSWORD';

        // WHEN.
        new Password($password);
    }
    public function testCanInstantiateEmptyPassword(): void
    {
        // THEN.
        $this->expectException(\InvalidArgumentException::class);

        // GIVEN.
        $password = '';

        // WHEN.
        new Password($password);
    }
}
