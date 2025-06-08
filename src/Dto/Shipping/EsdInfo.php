<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\Shipping;

use Kwaadpepper\ChronopostApiPhp\Dto\Dto;

readonly class EsdInfo implements Dto
{
    /**
     * ESD Info
     *
     * @param string             $fullNumber The unique ESD full number.
     * @param string             $number     The ESD number.
     * @param \DateTimeImmutable $pickupDate The ESD pickup date.
     */
    public function __construct(
        public string $fullNumber,
        public string $number,
        public \DateTimeImmutable $pickupDate,
    ) {
    }
}
