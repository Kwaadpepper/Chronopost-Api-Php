<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\Shipping;

use Kwaadpepper\ChronopostApiPhp\Dto\Dto;

/**
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */
readonly class MultiParcelV4 implements Dto
{
    /**
     * MultiParcelV4
     *
     * @param \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\MultiParcelValue[] $multiParcelValue  The multi-parcel value.
     * @param string|null                                                   $reservationNumber Only with `withReservation` on 1 or 2.
     * @param \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\EsdInfo|null       $esdInfo           ESD information, if available.
     *
     * @throws \InvalidArgumentException If multiParcelValue contains invalid values.
     * @phpstan-ignore throws.unusedType
     */
    public function __construct(
        public array $multiParcelValue,
        /** Only with `withReservation` on 1 or 2 */
        public string|null $reservationNumber = null,
        public EsdInfo|null $esdInfo = null,
    ) {
        foreach ($multiParcelValue as $parcelValue) {
            // @phpstan-ignore instanceof.alwaysTrue
            if (!($parcelValue instanceof MultiParcelValue)) {
                throw new \InvalidArgumentException('Multi parcel value must be an array of ' . MultiParcelValue::class);
            }
        }
    }
}
