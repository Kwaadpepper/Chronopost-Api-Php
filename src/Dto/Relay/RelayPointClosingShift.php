<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\Relay;

use Kwaadpepper\ChronopostApiPhp\Dto\Dto;

class RelayPointClosingShift implements Dto
{
    public function __construct(
        public readonly int $number,
        public readonly \DateTimeImmutable $from,
        public readonly \DateTimeImmutable $to
    ) {
    }
}
