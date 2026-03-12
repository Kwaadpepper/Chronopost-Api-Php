<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\Shipping;

use Kwaadpepper\ChronopostApiPhp\Dto\Dto;

/**
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */
readonly class ReservationMultiParcelResult implements Dto
{
    /**
     * ReservationMultiParcelResult
     *
     * @param \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\ReservationParcelValue[] $parcelValues      The parcel values.
     * @param string|null                                                         $reservationNumber The reservation number.
     * @param \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\EsdInfo|null             $esdInfo           ESD information, if available.
     *
     * @throws \InvalidArgumentException If parcelValues contains invalid values.
     * @phpstan-ignore throws.unusedType
     */
    public function __construct(
        public array $parcelValues,
        public string|null $reservationNumber = null,
        public EsdInfo|null $esdInfo = null,
    ) {
        foreach ($parcelValues as $parcelValue) {
            // @phpstan-ignore instanceof.alwaysTrue
            if (!($parcelValue instanceof ReservationParcelValue)) {
                throw new \InvalidArgumentException(
                    'Parcel values must be an array of ' . ReservationParcelValue::class,
                );
            }
        }
    }
}
