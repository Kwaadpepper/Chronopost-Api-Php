<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\Services\Tracking;

use ChronopostTracking\ServiceType\Track;
use ChronopostTracking\StructType\Event;
use ChronopostTracking\StructType\EventInfoComp;
use ChronopostTracking\StructType\InfoComp;
use ChronopostTracking\StructType\InfosPOD;
use ChronopostTracking\StructType\ListEventInfoComps;
use ChronopostTracking\StructType\ListEvents;
use ChronopostTracking\StructType\ResultTrackSearch;
use ChronopostTracking\StructType\ResultTrackSkybillV2;
use ChronopostTracking\StructType\ResultTrackWithSenderRef;
use ChronopostTracking\StructType\TrackESDResponse;
use ChronopostTracking\StructType\TrackSearchResponse;
use ChronopostTracking\StructType\TrackWithSenderRefResponse;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\EsdTrackResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SearchTrackResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SenderRefTrackResult;
use Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError;
use Kwaadpepper\ChronopostApiPhp\Exceptions\Tracking\TrackingException;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingSearchCriteria;
use Kwaadpepper\ChronopostApiPhp\Services\Tracking\TrackSearchService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 * @phpcs:disable Generic.Formatting.MultipleStatementAlignment.NotSameWarning
 */
class TrackSearchExtendedTest extends TestCase
{
    private Track&MockObject $trackServiceMock;

    private TrackSearchService $service;

    private AccountNumber $accountNumber;

    private Password $password;

    protected function setUp(): void
    {
        $this->trackServiceMock = $this->createMock(Track::class);
        $this->accountNumber = new AccountNumber('19869502');
        $this->password = new Password('255562');

        $this->service = new TrackSearchService(
            accountNumber: $this->accountNumber,
            password: $this->password,
            trackService: $this->trackServiceMock,
        );
    }

    public function testGivenSearchCriteriaWhenTrackSearchThenReturnsSearchTrackResults(): void
    {
        // GIVEN.
        $infosPod = new InfosPOD(
            skybillNumber: 'AB123456789CD',
            dateDeposit: '2025-01-15',
            depositCountry: 'FR',
            depositZipCode: '75001',
            recipientName: 'John Doe',
            recipientCity: 'Paris',
            recipientCountry: 'FR',
            recipientZipCode: '75002',
            shipperCity: 'Lyon',
            shipperZipCode: '69001',
            shipperRef: 'REF001',
            recipientRef: 'RECREF001',
            objectType: 'COLIS',
        );
        $infosPod->setSignificantEvent(new Event(
            code: 'D',
            eventDate: '2025-01-16 10:00:00',
            eventLabel: 'Delivered',
        ));

        $soapResult = new ResultTrackSearch(
            errorCode: 0,
            errorMessage: '',
            listInfosPOD: [$infosPod],
        );
        $soapResponse = new TrackSearchResponse(return: $soapResult);

        $this->trackServiceMock
            ->method('trackSearch')
            ->willReturn($soapResponse);

        // WHEN.
        $result = $this->service->trackSearch(
            new TrackingSearchCriteria(),
        );

        // THEN.
        self::assertInstanceOf(SearchTrackResult::class, $result);
        self::assertCount(1, $result->parcels);
        self::assertSame('AB123456789CD', $result->parcels[0]->skybillNumber);
        self::assertSame('John Doe', $result->parcels[0]->recipientName);
    }

    public function testGivenApiFailureWhenTrackSearchThenThrowsApiError(): void
    {
        // GIVEN.
        $this->trackServiceMock
            ->method('trackSearch')
            ->willReturn(false);

        $this->trackServiceMock
            ->method('getLastErrorForMethod')
            ->willReturn(new \SoapFault('Server', 'SOAP error'));

        // WHEN / THEN.
        $this->expectException(ApiError::class);
        $this->service->trackSearch(new TrackingSearchCriteria());
    }

    public function testGivenErrorCodeWhenTrackSearchThenThrowsTrackingException(): void
    {
        // GIVEN.
        $soapResult = new ResultTrackSearch(
            errorCode: 1,
            errorMessage: 'Invalid criteria',
        );
        $soapResponse = new TrackSearchResponse(return: $soapResult);

        $this->trackServiceMock
            ->method('trackSearch')
            ->willReturn($soapResponse);

        // WHEN / THEN.
        $this->expectException(TrackingException::class);
        $this->expectExceptionMessage('Invalid criteria');
        $this->service->trackSearch(new TrackingSearchCriteria());
    }

    public function testGivenSenderReferenceWhenTrackThenReturnsSenderRefResult(): void
    {
        // GIVEN.
        $event = new Event(
            code: 'D',
            eventDate: '2025-01-16 10:00:00',
            eventLabel: 'Delivered',
        );
        $listEvents = new ListEvents(
            skybillNumber: 'AB123456789CD',
            events: [$event],
        );

        $soapResult = new ResultTrackWithSenderRef(
            errorCode: 0,
            errorMessage: '',
            listParcel: [$listEvents],
        );
        $soapResponse = new TrackWithSenderRefResponse(return: $soapResult);

        $this->trackServiceMock
            ->method('trackWithSenderRef')
            ->willReturn($soapResponse);

        // WHEN.
        $result = $this->service->trackWithSenderRef(
            'MY-SENDER-REF',
        );

        // THEN.
        self::assertInstanceOf(SenderRefTrackResult::class, $result);
        self::assertCount(1, $result->parcels);
        self::assertSame('AB123456789CD', $result->parcels[0]->skybillNumber);
        self::assertCount(1, $result->parcels[0]->events);
    }

    public function testGivenApiFailureWhenTrackWithSenderRefThenThrowsApiError(): void
    {
        // GIVEN.
        $this->trackServiceMock
            ->method('trackWithSenderRef')
            ->willReturn(false);

        $this->trackServiceMock
            ->method('getLastErrorForMethod')
            ->willReturn(new \SoapFault('Server', 'SOAP error'));

        // WHEN / THEN.
        $this->expectException(ApiError::class);
        $this->service->trackWithSenderRef('REF');
    }

    public function testGivenErrorCodeWhenTrackWithSenderRefThenThrowsTrackingException(): void
    {
        // GIVEN.
        $soapResult = new ResultTrackWithSenderRef(
            errorCode: 3,
            errorMessage: 'Reference not found',
        );
        $soapResponse = new TrackWithSenderRefResponse(return: $soapResult);

        $this->trackServiceMock
            ->method('trackWithSenderRef')
            ->willReturn($soapResponse);

        // WHEN / THEN.
        $this->expectException(TrackingException::class);
        $this->expectExceptionMessage('Reference not found');
        $this->service->trackWithSenderRef('REF');
    }

    public function testGivenEsdNumberWhenTrackEsdThenReturnsEsdTrackResult(): void
    {
        // GIVEN.
        $eventInfoComp = new EventInfoComp();
        $eventInfoComp->setCode('P');
        $eventInfoComp->setEventDate('2025-01-15 08:30:00');
        $eventInfoComp->setEventLabel('Pris en charge');
        $eventInfoComp->setInfoCompList([new InfoComp(name: 'weight', value: '2.5')]);

        $listEventInfoComps = new ListEventInfoComps(
            skybillNumber: 'AB123456789CD',
            events: [$eventInfoComp],
        );

        $soapResult = new ResultTrackSkybillV2(
            errorCode: 0,
            errorMessage: '',
            listEventInfoComp: $listEventInfoComps,
        );
        $soapResponse = new TrackESDResponse(return: $soapResult);

        $this->trackServiceMock
            ->method('trackESD')
            ->willReturn($soapResponse);

        // WHEN.
        $result = $this->service->trackEsd('ESD123456789');

        // THEN.
        self::assertInstanceOf(EsdTrackResult::class, $result);
        self::assertCount(1, $result->events);
        self::assertSame('P', $result->events[0]->code);
    }

    public function testGivenApiFailureWhenTrackEsdThenThrowsApiError(): void
    {
        // GIVEN.
        $this->trackServiceMock
            ->method('trackESD')
            ->willReturn(false);

        $this->trackServiceMock
            ->method('getLastErrorForMethod')
            ->willReturn(new \SoapFault('Server', 'SOAP error'));

        // WHEN / THEN.
        $this->expectException(ApiError::class);
        $this->service->trackEsd('ESD123456789');
    }

    public function testGivenErrorCodeWhenTrackEsdThenThrowsTrackingException(): void
    {
        // GIVEN.
        $soapResult = new ResultTrackSkybillV2(
            errorCode: 5,
            errorMessage: 'ESD not found',
        );
        $soapResponse = new TrackESDResponse(return: $soapResult);

        $this->trackServiceMock
            ->method('trackESD')
            ->willReturn($soapResponse);

        // WHEN / THEN.
        $this->expectException(TrackingException::class);
        $this->expectExceptionMessage('ESD not found');
        $this->service->trackEsd('ESD123456789');
    }
}
