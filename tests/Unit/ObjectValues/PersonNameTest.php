<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\ObjectValues;

use Kwaadpepper\ChronopostApiPhp\ObjectValues\PersonName;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class PersonNameTest extends TestCase
{
    public function testCanInstantiateValidName(): void
    {
        // GIVEN.
        $name = 'Jean-Pierre Dupont';

        // WHEN.
        $personName = new PersonName($name);

        // THEN.
        $this->assertSame('Jean-Pierre Dupont', $personName->getValue());
    }

    public function testCanInstantiateAccentedName(): void
    {
        // GIVEN.
        $name = 'Hélène François';

        // WHEN.
        $personName = new PersonName($name);

        // THEN.
        $this->assertSame('Hélène François', $personName->getValue());
    }

    public function testCannotInstantiateEmptyName(): void
    {
        // THEN.
        $this->expectException(\InvalidArgumentException::class);

        // GIVEN / WHEN.
        new PersonName('');
    }

    public function testCannotInstantiateTooLongName(): void
    {
        // THEN.
        $this->expectException(\InvalidArgumentException::class);

        // GIVEN / WHEN.
        new PersonName(str_repeat('A', 101));
    }

    public function testCannotInstantiateWithDigits(): void
    {
        // THEN.
        $this->expectException(\InvalidArgumentException::class);

        // GIVEN / WHEN.
        new PersonName('Jean123');
    }

    public function testToString(): void
    {
        // GIVEN.
        $personName = new PersonName('Marie');

        // WHEN / THEN.
        $this->assertSame('Marie', (string) $personName);
    }

    public function testCanInstantiateWithApostrophe(): void
    {
        // GIVEN.
        $name = "D'Artagnan";

        // WHEN.
        $personName = new PersonName($name);

        // THEN.
        $this->assertSame("D'Artagnan", $personName->getValue());
    }
}
