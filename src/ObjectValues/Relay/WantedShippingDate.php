<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay;

use InvalidArgumentException;
use DateTimeImmutable;
use DateTimeInterface;

final class WantedShippingDate
{
    public function __construct(
        public readonly DateTimeInterface $date,
    ) {
        $now = new DateTimeImmutable('today');

        $inputDate = DateTimeImmutable::createFromInterface($date)->setTime(0, 0);

        if ($inputDate < $now) {
            throw new InvalidArgumentException('Wanted shipping date cannot be in the past.');
        }

        $maxDate = $now->modify('+30 days');
        if ($inputDate > $maxDate) {
            throw new InvalidArgumentException('Wanted shipping date cannot be more than 30 days in the future.');
        }
    }
}
