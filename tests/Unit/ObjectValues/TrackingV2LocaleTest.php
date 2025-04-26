<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\ObjectValues;

use Kwaadpepper\ChronopostApiPhp\Enums\Locale;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingV2Locale;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class TrackingV2LocaleTest extends TestCase
{
    public function testCanCreateValidTrackingV2Locale(): void
    {
        // GIVEN.
        $locale = Locale::FR;

        // WHEN.
        TrackingV2Locale::create($locale);

        // THEN.
        $this->expectNotToPerformAssertions();
    }

    public function testCanCreateLocalUsingString(): void
    {
        // GIVEN.
        $locale = 'fr_FR';

        // WHEN.
        TrackingV2Locale::createFromString($locale);

        // THEN.
        $this->expectNotToPerformAssertions();
    }

    public function testCannotCreateInvalidTrackingV2Locale(): void
    {
        // THEN.
        $this->expectException(\InvalidArgumentException::class);

        // GIVEN.
        $locale = Locale::US;

        // WHEN.
        TrackingV2Locale::create($locale);
    }
}
