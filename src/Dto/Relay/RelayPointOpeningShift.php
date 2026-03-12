<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\Relay;

use Kwaadpepper\ChronopostApiPhp\Dto\Dto;

class RelayPointOpeningShift implements Dto
{
    public function __construct(
        public readonly int $dayOfWeek,
        public readonly string $morningStartingTime,
        public readonly string $morningClosingTime,
        public readonly string $afternoonStartingTime,
        public readonly string $afternoonClosingTime,
        public readonly string $timeAsString
    ) {
    }

    public function getWeekDayLabel(): string
    {
        return match ($this->dayOfWeek) {
            1 => 'Lundi',
            2 => 'Mardi',
            3 => 'Mercredi',
            4 => 'Jeudi',
            5 => 'Vendredi',
            6 => 'Samedi',
            7 => 'Dimanche',
            default => 'Inconnu',
        };
    }
}
