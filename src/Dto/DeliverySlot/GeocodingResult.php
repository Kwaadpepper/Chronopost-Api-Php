<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot;

use Kwaadpepper\ChronopostApiPhp\Dto\Dto;

/**
 * Result of an address geocoding request.
 */
class GeocodingResult implements Dto
{
    public function __construct(
        public readonly ?float $latitude,
        public readonly ?float $longitude,
        public readonly ?int $qualityLevel,
    ) {
    }
}
