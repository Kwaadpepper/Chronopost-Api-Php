<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\Tracking;

use Kwaadpepper\ChronopostApiPhp\Dto\Dto;

readonly class SenderRefTrackResult implements Dto
{
    /**
     * @param ParcelEvents[] $parcels
     *
     * @throws \InvalidArgumentException If $parcels contains non-ParcelEvents values.
     * @phpstan-ignore throws.unusedType
     */
    public function __construct(
        public array $parcels = [],
    ) {
        foreach ($parcels as $parcel) {
            // @phpstan-ignore instanceof.alwaysTrue
            if (!$parcel instanceof ParcelEvents) {
                throw new \InvalidArgumentException('Parcels must be an array of ' . ParcelEvents::class);
            }
        }
    }
}
