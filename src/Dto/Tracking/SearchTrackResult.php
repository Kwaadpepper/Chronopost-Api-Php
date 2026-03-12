<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\Tracking;

use Kwaadpepper\ChronopostApiPhp\Dto\Dto;

readonly class SearchTrackResult implements Dto
{
    /**
     * @param ParcelInfo[] $parcels
     *
     * @throws \InvalidArgumentException If $parcels contains non-ParcelInfo values.
     * @phpstan-ignore throws.unusedType
     */
    public function __construct(
        public array $parcels = [],
    ) {
        foreach ($parcels as $parcel) {
            // @phpstan-ignore instanceof.alwaysTrue
            if (!$parcel instanceof ParcelInfo) {
                throw new \InvalidArgumentException('Parcels must be an array of ' . ParcelInfo::class);
            }
        }
    }
}
