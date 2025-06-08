<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel;

use Kwaadpepper\ChronopostApiPhp\Enums\SkyBillMode;
use Kwaadpepper\ChronopostApiPhp\Enums\SkyBillWithReservation;

readonly class SkyBillParameters
{
    /**
     * SkyBillParameter
     *
     * @param \Kwaadpepper\ChronopostApiPhp\Enums\SkyBillMode            $mode
     * @param boolean                                                    $duplicata
     * @param \Kwaadpepper\ChronopostApiPhp\Enums\SkyBillWithReservation $reservation
     */
    public function __construct(
        public SkyBillMode $mode = SkyBillMode::PDF,
        public bool $duplicata = false,
        public SkyBillWithReservation $reservation = SkyBillWithReservation::DEFAULT_NO_RESERVATION,
    ) {
    }
}
