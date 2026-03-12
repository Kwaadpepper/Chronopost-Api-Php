<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Factory;

use ChronopostRelay\StructType\PointChronopost;
use Kwaadpepper\ChronopostApiPhp\Dto\Relay\RelayPointBasic;
use Kwaadpepper\ChronopostApiPhp\Enums\CountryForChronopost;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayId;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayPointType;

class RelayPointBasicFactory implements Factory
{
    public function __construct()
    {
    }

    /**
     * Create RelayPointBasic DTOs from an array of PointChronopost.
     *
     * @param  PointChronopost[] $points
     * @return RelayPointBasic[]
     */
    public function createFromArray(array $points): array
    {
        return array_map([$this, 'mapPoint'], $points);
    }

    /** @param \ChronopostRelay\StructType\PointChronopost $response */
    public function create($response): RelayPointBasic
    {
        return $this->mapPoint($response);
    }

    private function mapPoint(PointChronopost $point): RelayPointBasic
    {
        $postCode = new PostCode(
            $point->getCodePostal() ?? '',
            CountryForChronopost::FRANCE,
        );
        $relayId = new RelayId($point->getIdentifiantChronopost() ?? '');
        $relayType = RelayPointType::tryFrom($point->getTypeDePoint() ?? '') ?? RelayPointType::ANY;

        return new RelayPointBasic(
            $point->getAdresse1() ?? '',
            $point->getAdresse2() ?? '',
            $point->getAdresse3() ?? '',
            $postCode,
            $relayId,
            $point->getLocalite() ?? '',
            $point->getNomEnseigne() ?? '',
            $relayType,
            $point->getDateArriveColis(),
            $point->getHorairesOuverturesFormates() ?? '',
            $point->getHorairesOuvertureLundi() ?? '',
            $point->getHorairesOuvertureMardi() ?? '',
            $point->getHorairesOuvertureMercredi() ?? '',
            $point->getHorairesOuvertureJeudi() ?? '',
            $point->getHorairesOuvertureVendredi() ?? '',
            $point->getHorairesOuvertureSamedi() ?? '',
            $point->getHorairesOuvertureDimanche() ?? '',
        );
    }
}
