<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Facade;

use Kwaadpepper\ChronopostApiPhp\Dto\Relay\RelaySearchResult;
use Kwaadpepper\ChronopostApiPhp\Enums\CountryForChronopost;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AddressSearch;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Coordinates;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ProductCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayId;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayPointType;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayServiceType;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\WantedShippingDate;
use Kwaadpepper\ChronopostApiPhp\Services\RelayPoint\RelayPointService;

class RelayFacade
{
    public function __construct(
        private RelayPointService $relayPointService,
    ) {
    }

    /**
     * Find relay points using search criteria.
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Relay\RelaySearchResult
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Relay\RelaySearchException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     */
    public function searchRelayPoint(
        ProductCode $productCode,
        AddressSearch $addressSearch,
        WantedShippingDate $wantedShippingDate,
        RelayPointType $relayPointType = RelayPointType::ANY,
        RelayServiceType $relayServiceType = RelayServiceType::ANY,
        ?float $weight = null,
        ?int $maxResults = null,
        ?int $radiusInKm = null,
    ): RelaySearchResult {
        return $this->relayPointService->searchRelayPoint(
            $productCode,
            $addressSearch,
            $wantedShippingDate,
            $relayPointType,
            $relayServiceType,
            $weight,
            $maxResults,
            $radiusInKm,
        );
    }

    /**
     * Find relay points by GPS coordinates.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Relay\RelaySearchException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     */
    public function searchRelayPointByCoordinates(
        Coordinates $coordinates,
        ProductCode $productCode,
        WantedShippingDate $wantedShippingDate,
        RelayPointType $relayPointType = RelayPointType::ANY,
        RelayServiceType $relayServiceType = RelayServiceType::ANY,
        ?float $weight = null,
        ?int $maxResults = null,
        ?int $radiusInKm = null,
    ): RelaySearchResult {
        return $this->relayPointService->searchRelayPointByCoordinates(
            $coordinates,
            $productCode,
            $wantedShippingDate,
            $relayPointType,
            $relayServiceType,
            $weight,
            $maxResults,
            $radiusInKm,
        );
    }

    /**
     * Find a relay point by its unique identifier.
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Relay\RelayPointBasic[]
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     */
    public function searchRelayPointById(RelayId $relayId): array
    {
        return $this->relayPointService->searchRelayPointById($relayId);
    }

    /**
     * Get detailed information about a relay point.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Relay\RelaySearchException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     */
    public function getRelayPointDetail(RelayId $relayId): RelaySearchResult
    {
        return $this->relayPointService->getRelayPointDetail($relayId);
    }

    /**
     * Get detailed information about an international relay point.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Relay\RelaySearchException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     */
    public function getInternationalRelayPointDetail(
        RelayId $relayId,
        CountryForChronopost $country,
        string $language = 'FR',
        string $version = '2.0',
    ): RelaySearchResult {
        return $this->relayPointService->getInternationalRelayPointDetail(
            $relayId,
            $country,
            $language,
            $version,
        );
    }
}
