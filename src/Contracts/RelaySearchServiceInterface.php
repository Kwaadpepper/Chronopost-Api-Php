<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Contracts;

use Kwaadpepper\ChronopostApiPhp\Dto\Relay\RelaySearchResult;
use Kwaadpepper\ChronopostApiPhp\Enums\CountryForChronopost;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AddressSearch;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Coordinates;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ProductCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayId;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayPointType;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayServiceType;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\WantedShippingDate;

interface RelaySearchServiceInterface
{
    public function searchRelayPoint(
        ProductCode $productCode,
        AddressSearch $addressSearch,
        WantedShippingDate $wantedShippingDate,
        RelayPointType $relayPointType = RelayPointType::ANY,
        RelayServiceType $relayServiceType = RelayServiceType::ANY,
        ?float $weight = null,
        ?int $maxResults = null,
        ?int $radiusInKm = null,
        string $language = 'FR',
        string $version = '2.0',
    ): RelaySearchResult;

    public function searchRelayPointByCoordinates(
        Coordinates $coordinates,
        ProductCode $productCode,
        WantedShippingDate $wantedShippingDate,
        RelayPointType $relayPointType = RelayPointType::ANY,
        RelayServiceType $relayServiceType = RelayServiceType::ANY,
        ?float $weight = null,
        ?int $maxResults = null,
        ?int $radiusInKm = null,
    ): RelaySearchResult;

    /**
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Relay\RelayPointBasic[]
     */
    public function searchRelayPointById(
        RelayId $relayId,
    ): array;

    public function getRelayPointDetail(
        RelayId $relayId,
    ): RelaySearchResult;

    public function getInternationalRelayPointDetail(
        RelayId $relayId,
        CountryForChronopost $country,
        string $language = 'FR',
        string $version = '2.0',
    ): RelaySearchResult;
}
