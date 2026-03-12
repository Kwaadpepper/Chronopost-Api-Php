<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\Relay;

use Kwaadpepper\ChronopostApiPhp\Dto\Dto;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayId;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayPointType;

/**
 * Basic relay point information as returned by recherchePointChronopostParId.
 * Contains less detail than RelayPoint (no coordinates, distance, accessibility, etc.).
 */
class RelayPointBasic implements Dto
{
    public function __construct(
        public readonly string $address1,
        public readonly string $address2,
        public readonly string $address3,
        public readonly PostCode $postcode,
        public readonly RelayId $relayId,
        public readonly string $city,
        public readonly string $name,
        public readonly RelayPointType $type,
        public readonly ?string $parcelArrivalDate,
        public readonly string $formattedOpeningHours,
        public readonly string $mondayHours,
        public readonly string $tuesdayHours,
        public readonly string $wednesdayHours,
        public readonly string $thursdayHours,
        public readonly string $fridayHours,
        public readonly string $saturdayHours,
        public readonly string $sundayHours,
    ) {
    }
}
