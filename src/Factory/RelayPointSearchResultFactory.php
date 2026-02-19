<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Factory;

use ChronopostRelay\StructType\ListeHoraireOuverturePourUnJour;
use ChronopostRelay\StructType\PeriodeFermeture;
use ChronopostRelay\StructType\PointCHR;
use Kwaadpepper\ChronopostApiPhp\Dto\Relay\RelayPoint;
use Kwaadpepper\ChronopostApiPhp\Dto\Relay\RelayPointClosingShift;
use Kwaadpepper\ChronopostApiPhp\Dto\Relay\RelayPointOpeningShift;
use Kwaadpepper\ChronopostApiPhp\Dto\Relay\RelaySearchResult;
use Kwaadpepper\ChronopostApiPhp\Enums\CountryForChronopost;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Coordinates;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayId;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayPointQualityResult;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayPointType;

class RelayPointSearchResultFactory implements Factory
{
    /**
     * Create a new instance of the factory.
     */
    public function __construct()
    {
        // No initialization required for this factory.
    }

    /** @param \ChronopostRelay\StructType\PointCHRResult $response */
    public function create($response): RelaySearchResult
    {
        return new RelaySearchResult(
            RelayPointQualityResult::from($response->getQualiteReponse()),
            array_map([$this, 'mapRelayPoint'], $response->getListePointRelais())
        );
    }

    private function mapRelayPoint(PointCHR $point): RelayPoint
    {
        $codePays = $point->getCodePays();
        $country = CountryForChronopost::tryFrom($codePays ? intval($codePays) : null) ?? CountryForChronopost::FRANCE;
        $postCode = new PostCode($point->getCodePostal(), $country);
        $coordinates = new Coordinates(
            floatval($point->getCoordGeolocalisationLatitude()),
            floatval($point->getCoordGeolocalisationLongitude())
        );
        $relayId = new RelayId($point->getIdentifiant());
        $relayType = RelayPointType::tryFrom($point->getTypeDePoint()) ?? RelayPointType::ANY;

        $oppeningShifts = $point->getListeHoraireOuverture();
        $closingShifts = $point->getListePeriodeFermeture();

        return new RelayPoint(
            $point->getAccesPersonneMobiliteReduite() ?? false,
            $point->getActif() ?? false,
            $point->getAdresse1(),
            $point->getAdresse2(),
            $point->getAdresse3(),
            $postCode,
            $coordinates,
            $point->getDistanceEnMetre(),
            $relayId,
            $point->getIndiceDeLocalisation(),
            $point->getLocalite(),
            $point->getNom(),
            $point->getPoidsMaxi(),
            $relayType,
            $point->getUrlGoogleMaps(),
            $oppeningShifts ? array_map([$this, 'mapToOpeningShift'], $oppeningShifts) : [],
            $closingShifts ? array_map([$this, 'mapToClosingShift'], $closingShifts) : [],
        );
    }

    private function mapToOpeningShift(ListeHoraireOuverturePourUnJour $openingShift): RelayPointOpeningShift
    {
        return new RelayPointOpeningShift(
            $openingShift->getJour(),
            '',
            '',
            '',
            '',
            $openingShift->getHorairesAsString(),
        );
    }

    private function mapToClosingShift(PeriodeFermeture $closingShift): RelayPointClosingShift
    {
        return new RelayPointClosingShift(
            $closingShift->getNumero(),
            new \DateTimeImmutable($closingShift->getCalendarDeDebut()),
            new \DateTimeImmutable($closingShift->getCalendarDeFin()),
        );
    }
}
