<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\Facade;

use Kwaadpepper\ChronopostApiPhp\Dto\Relay\RelaySearchResult;
use Kwaadpepper\ChronopostApiPhp\Enums\CountryForChronopost;
use Kwaadpepper\ChronopostApiPhp\Facade\RelayFacade;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AddressSearch;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Coordinates;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ProductCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayId;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\WantedShippingDate;
use Kwaadpepper\ChronopostApiPhp\Services\RelayPoint\RelayPointService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class RelayFacadeTest extends TestCase
{
    private RelayPointService&MockObject $relayServiceMock;

    private RelayFacade $facade;

    protected function setUp(): void
    {
        $this->relayServiceMock = $this->createMock(RelayPointService::class);
        $this->facade = new RelayFacade($this->relayServiceMock);
    }

    public function testSearchRelayPointDelegatesToService(): void
    {
        // GIVEN.
        $productCode = new ProductCode('86');
        $address     = new AddressSearch(new PostCode('75001', CountryForChronopost::FRANCE), 'Paris');
        $date        = new WantedShippingDate(new \DateTimeImmutable('2026-03-15'));
        $expected    = $this->createMock(RelaySearchResult::class);

        $this->relayServiceMock->method('searchRelayPoint')
            ->willReturn($expected);

        // WHEN.
        $result = $this->facade->searchRelayPoint($productCode, $address, $date);

        // THEN.
        $this->assertSame($expected, $result);
    }

    public function testSearchRelayPointByCoordinatesDelegatesToService(): void
    {
        // GIVEN.
        $coords      = new Coordinates(48.8566, 2.3522);
        $productCode = new ProductCode('86');
        $date        = new WantedShippingDate(new \DateTimeImmutable('2026-03-15'));
        $expected    = $this->createMock(RelaySearchResult::class);

        $this->relayServiceMock->method('searchRelayPointByCoordinates')
            ->willReturn($expected);

        // WHEN.
        $result = $this->facade->searchRelayPointByCoordinates($coords, $productCode, $date);

        // THEN.
        $this->assertSame($expected, $result);
    }

    public function testSearchRelayPointByIdDelegatesToService(): void
    {
        // GIVEN.
        $relayId  = new RelayId('12345');
        $expected = [];
        $this->relayServiceMock->method('searchRelayPointById')
            ->with($relayId)
            ->willReturn($expected);

        // WHEN.
        $result = $this->facade->searchRelayPointById($relayId);

        // THEN.
        $this->assertSame($expected, $result);
    }

    public function testGetRelayPointDetailDelegatesToService(): void
    {
        // GIVEN.
        $relayId  = new RelayId('12345');
        $expected = $this->createMock(RelaySearchResult::class);
        $this->relayServiceMock->method('getRelayPointDetail')
            ->with($relayId)
            ->willReturn($expected);

        // WHEN.
        $result = $this->facade->getRelayPointDetail($relayId);

        // THEN.
        $this->assertSame($expected, $result);
    }

    public function testGetInternationalRelayPointDetailDelegatesToService(): void
    {
        // GIVEN.
        $relayId  = new RelayId('67890');
        $expected = $this->createMock(RelaySearchResult::class);
        $this->relayServiceMock->method('getInternationalRelayPointDetail')
            ->willReturn($expected);

        // WHEN.
        $result = $this->facade->getInternationalRelayPointDetail(
            $relayId,
            CountryForChronopost::BELGIQUE,
        );

        // THEN.
        $this->assertSame($expected, $result);
    }
}
