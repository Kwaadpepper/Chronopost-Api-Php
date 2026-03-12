<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues;

class Coordinates
{
    public function __construct(
        public readonly float $latitude,
        public readonly float $longitude,
    ) {
        if ($latitude < -90.0 || $latitude > 90.0) {
            throw new \InvalidArgumentException("Latitude must be between -90 and 90, got {$latitude}");
        }

        if ($longitude < -180.0 || $longitude > 180.0) {
            throw new \InvalidArgumentException("Longitude must be between -180 and 180, got {$longitude}");
        }
    }
}
