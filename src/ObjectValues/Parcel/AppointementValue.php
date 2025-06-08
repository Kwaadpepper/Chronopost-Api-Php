<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel;

readonly class AppointementValue
{
    /**
     * AppointementValue
     *
     * @param \DateTimeImmutable $start Start date and time of the appointment.
     * @param \DateTimeImmutable $end   End date and time of the appointment.
     */
    public function __construct(
        public \DateTimeImmutable $start,
        public \DateTimeImmutable $end,
    ) {
    }
}
