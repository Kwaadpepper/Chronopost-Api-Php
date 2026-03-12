<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\ObjectValues;

use Kwaadpepper\ChronopostApiPhp\ObjectValues\DateRange;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class DateRangeTest extends TestCase
{
    public function testCanInstantiateValidDateRange(): void
    {
        // GIVEN.
        $begin = new \DateTimeImmutable('2024-01-01');
        $end   = new \DateTimeImmutable('2024-01-31');

        // WHEN.
        $range = new DateRange($begin, $end);

        // THEN.
        $this->assertSame($begin, $range->getBegin());
        $this->assertSame($end, $range->getEnd());
    }

    public function testCanInstantiateSameDateRange(): void
    {
        // GIVEN.
        $date = new \DateTimeImmutable('2024-06-15');

        // WHEN.
        $range = new DateRange($date, $date);

        // THEN.
        $this->assertSame($date, $range->getBegin());
        $this->assertSame($date, $range->getEnd());
    }

    public function testCannotInstantiateReversedDateRange(): void
    {
        // THEN.
        $this->expectException(\InvalidArgumentException::class);

        // GIVEN.
        $begin = new \DateTimeImmutable('2024-12-31');
        $end   = new \DateTimeImmutable('2024-01-01');

        // WHEN.
        new DateRange($begin, $end);
    }
}
