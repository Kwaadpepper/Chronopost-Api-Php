<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\ObjectValues;

use Kwaadpepper\ChronopostApiPhp\ObjectValues\Email;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class EmailTest extends TestCase
{
    public function testCanInstantiateValidEmail(): void
    {
        // GIVEN.
        $value = 'user@example.com';

        // WHEN.
        $email = new Email($value);

        // THEN.
        $this->assertSame('user@example.com', $email->getValue());
    }

    public function testCannotInstantiateInvalidEmail(): void
    {
        // THEN.
        $this->expectException(\InvalidArgumentException::class);

        // GIVEN / WHEN.
        new Email('not-an-email');
    }

    public function testCannotInstantiateEmptyEmail(): void
    {
        // THEN.
        $this->expectException(\InvalidArgumentException::class);

        // GIVEN / WHEN.
        new Email('');
    }

    public function testCannotInstantiateTooLongEmail(): void
    {
        // THEN.
        $this->expectException(\InvalidArgumentException::class);

        // GIVEN.
        $longEmail = str_repeat('a', 70) . '@example.com';

        // WHEN.
        new Email($longEmail);
    }

    public function testToString(): void
    {
        // GIVEN.
        $email = new Email('test@domain.fr');

        // WHEN / THEN.
        $this->assertSame('test@domain.fr', (string) $email);
    }
}
