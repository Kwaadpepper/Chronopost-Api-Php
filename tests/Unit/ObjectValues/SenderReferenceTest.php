<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\ObjectValues;

use Kwaadpepper\ChronopostApiPhp\ObjectValues\SenderReference;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class SenderReferenceTest extends TestCase
{
    public function testCanInstantiateValidReference(): void
    {
        // GIVEN.
        $ref = 'ABC-123_test';

        // WHEN.
        $senderRef = new SenderReference($ref);

        // THEN.
        $this->assertSame('ABC-123_test', $senderRef->getValue());
    }

    public function testCanInstantiateMaxLengthReference(): void
    {
        // GIVEN.
        $ref = str_repeat('A', 35);

        // WHEN.
        $senderRef = new SenderReference($ref);

        // THEN.
        $this->assertSame($ref, $senderRef->getValue());
    }

    public function testCannotInstantiateEmptyReference(): void
    {
        // THEN.
        $this->expectException(\InvalidArgumentException::class);

        // GIVEN / WHEN.
        new SenderReference('');
    }

    public function testCannotInstantiateTooLongReference(): void
    {
        // THEN.
        $this->expectException(\InvalidArgumentException::class);

        // GIVEN / WHEN.
        new SenderReference(str_repeat('A', 36));
    }

    public function testCannotInstantiateWithInvalidChars(): void
    {
        // THEN.
        $this->expectException(\InvalidArgumentException::class);

        // GIVEN / WHEN.
        new SenderReference('REF@#$%');
    }

    public function testToString(): void
    {
        // GIVEN.
        $senderRef = new SenderReference('REF-001');

        // WHEN / THEN.
        $this->assertSame('REF-001', (string) $senderRef);
    }
}
