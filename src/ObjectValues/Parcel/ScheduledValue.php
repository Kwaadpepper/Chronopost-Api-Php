<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel;

readonly class ScheduledValue
{
    /**
     * ScheduledValue
     *
     * @param AppointementValue       $appointement   The appointment details.
     * @param \DateTimeImmutable|null $expirationDate Date limite de consommation, Mandatory for Chronofresh products.
     * @param \DateTimeImmutable|null $sellByDate     Date limite de vente.
     */
    public function __construct(
        public AppointementValue $appointement,
        public \DateTimeImmutable|null $expirationDate = null,
        public \DateTimeImmutable|null $sellByDate = null,
    ) {
    }
}
