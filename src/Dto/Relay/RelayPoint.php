<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\Relay;

use Kwaadpepper\ChronopostApiPhp\Dto\Dto;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Coordinates;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayId;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayPointType;

class RelayPoint implements Dto
{
    public function __construct(
        public readonly bool $accessibleToReducedMobilityPersons,
        public readonly bool $active,
        public readonly string $address1,
        public readonly string $address2,
        public readonly string $address3,
        public readonly PostCode $postcode,
        public readonly Coordinates $coordinates,
        public readonly int $distanceFromClientInMeters,
        public readonly RelayId $relayId,
        public readonly string $informationComplementary,
        public readonly string $city,
        public readonly string $name,
        public readonly int $maxWeightInKiloGrams,
        public readonly RelayPointType $type,
        public readonly string $googleMapUri,
        /** @var RelayPointOpeningShift[] $openingShifts */
        public readonly array $openingShifts,
        /** @var RelayPointClosingShift[] $closingShifts */
        public readonly array $closingShifts,
    ) {
    }
}
