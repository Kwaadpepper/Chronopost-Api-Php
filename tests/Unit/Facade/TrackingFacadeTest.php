<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\Facade;

use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\CancelListResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\CancelResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\EsdTrackResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\ProofOfDelivery;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\ProofOfDeliveryByRef;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SearchTrackResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SenderRefTrackResult;
use Kwaadpepper\ChronopostApiPhp\Facade\TrackingFacade;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingSearchCriteria;
use Kwaadpepper\ChronopostApiPhp\Services\Tracking\ProofOfDeliveryService;
use Kwaadpepper\ChronopostApiPhp\Services\Tracking\TrackCancelService;
use Kwaadpepper\ChronopostApiPhp\Services\Tracking\TrackSearchService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class TrackingFacadeTest extends TestCase
{
    private TrackSearchService&MockObject $trackSearchMock;

    private TrackCancelService&MockObject $trackCancelMock;

    private ProofOfDeliveryService&MockObject $podMock;

    private TrackingFacade $facade;

    protected function setUp(): void
    {
        $this->trackSearchMock = $this->createMock(TrackSearchService::class);
        $this->trackCancelMock = $this->createMock(TrackCancelService::class);
        $this->podMock         = $this->createMock(ProofOfDeliveryService::class);

        $this->facade = new TrackingFacade(
            $this->trackSearchMock,
            $this->trackCancelMock,
            $this->podMock,
        );
    }

    public function testTrackShipmentDelegatesToService(): void
    {
        // GIVEN.
        $tn       = new TrackingNumber('AB123456789CD');
        $expected = [];
        $this->trackSearchMock->method('findUsingTrackingNumber')
            ->with($tn)
            ->willReturn($expected);

        // WHEN.
        $result = $this->facade->trackShipment($tn);

        // THEN.
        $this->assertSame($expected, $result);
    }

    public function testTrackBySearchQueryDelegatesToService(): void
    {
        // GIVEN.
        $criteria = $this->createMock(TrackingSearchCriteria::class);
        $expected = $this->createMock(SearchTrackResult::class);
        $this->trackSearchMock->method('trackSearch')
            ->with($criteria)
            ->willReturn($expected);

        // WHEN.
        $result = $this->facade->trackBySearchQuery($criteria);

        // THEN.
        $this->assertSame($expected, $result);
    }

    public function testTrackBySenderReferenceDelegatesToService(): void
    {
        // GIVEN.
        $expected = $this->createMock(SenderRefTrackResult::class);
        $this->trackSearchMock->method('trackWithSenderRef')
            ->with('REF001')
            ->willReturn($expected);

        // WHEN.
        $result = $this->facade->trackBySenderReference('REF001');

        // THEN.
        $this->assertSame($expected, $result);
    }

    public function testTrackEsdDelegatesToService(): void
    {
        // GIVEN.
        $expected = $this->createMock(EsdTrackResult::class);
        $this->trackSearchMock->method('trackEsd')
            ->with('ESD001')
            ->willReturn($expected);

        // WHEN.
        $result = $this->facade->trackEsd('ESD001');

        // THEN.
        $this->assertSame($expected, $result);
    }

    public function testCancelShipmentDelegatesToService(): void
    {
        // GIVEN.
        $tn       = new TrackingNumber('AB123456789CD');
        $expected = $this->createMock(CancelResult::class);
        $this->trackCancelMock->method('cancelSkybill')
            ->with($tn)
            ->willReturn($expected);

        // WHEN.
        $result = $this->facade->cancelShipment($tn);

        // THEN.
        $this->assertSame($expected, $result);
    }

    public function testCancelMultipleShipmentsDelegatesToService(): void
    {
        // GIVEN.
        $tns      = [new TrackingNumber('AB123456789CD')];
        $expected = $this->createMock(CancelListResult::class);
        $this->trackCancelMock->method('cancelListSkybill')
            ->with($tns)
            ->willReturn($expected);

        // WHEN.
        $result = $this->facade->cancelMultipleShipments($tns);

        // THEN.
        $this->assertSame($expected, $result);
    }

    public function testGetProofOfDeliveryDelegatesToService(): void
    {
        // GIVEN.
        $tn       = new TrackingNumber('AB123456789CD');
        $expected = $this->createMock(ProofOfDelivery::class);
        $this->podMock->method('searchPod')
            ->with($tn, true)
            ->willReturn($expected);

        // WHEN.
        $result = $this->facade->getProofOfDelivery($tn);

        // THEN.
        $this->assertSame($expected, $result);
    }

    public function testGetProofOfDeliveryByReferenceDelegatesToService(): void
    {
        // GIVEN.
        $expected = $this->createMock(ProofOfDeliveryByRef::class);
        $this->podMock->method('searchPodWithSenderRef')
            ->with('SREF01', false)
            ->willReturn($expected);

        // WHEN.
        $result = $this->facade->getProofOfDeliveryByReference('SREF01', false);

        // THEN.
        $this->assertSame($expected, $result);
    }
}
