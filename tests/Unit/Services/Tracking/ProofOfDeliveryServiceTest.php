<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\Services\Tracking;

use ChronopostTracking\ServiceType\Search;
use ChronopostTracking\StructType\ParcelPOD;
use ChronopostTracking\StructType\ResultSearchPOD;
use ChronopostTracking\StructType\ResultSearchPODWithSenderRef;
use ChronopostTracking\StructType\SearchPODResponse;
use ChronopostTracking\StructType\SearchPODWithSenderRefResponse;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\ProofOfDelivery;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\ProofOfDeliveryByRef;
use Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError;
use Kwaadpepper\ChronopostApiPhp\Exceptions\Tracking\TrackingException;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingNumber;
use Kwaadpepper\ChronopostApiPhp\Services\Tracking\ProofOfDeliveryService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 * @phpcs:disable Generic.Formatting.MultipleStatementAlignment.NotSameWarning
 */
class ProofOfDeliveryServiceTest extends TestCase
{
    private Search&MockObject $searchServiceMock;

    private ProofOfDeliveryService $service;

    private AccountNumber $accountNumber;

    private Password $password;

    protected function setUp(): void
    {
        $this->searchServiceMock = $this->createMock(Search::class);
        $this->accountNumber = new AccountNumber('19869502');
        $this->password = new Password('255562');

        $this->service = new ProofOfDeliveryService(
            accountNumber: $this->accountNumber,
            password: $this->password,
            searchService: $this->searchServiceMock,
        );
    }

    public function testGivenValidTrackingNumberWhenSearchPodThenReturnsProof(): void
    {
        // GIVEN.
        $trackingNumber = new TrackingNumber('AB123456789CD');
        $soapResult = new ResultSearchPOD(
            errorCode: 0,
            errorMessage: '',
            formatPOD: 'PDF',
            pod: base64_encode('fake-pdf-content'),
            podPresente: true,
            statusCode: 1,
        );
        $soapResponse = new SearchPODResponse(return: $soapResult);

        $this->searchServiceMock
            ->method('searchPOD')
            ->willReturn($soapResponse);

        // WHEN.
        $result = $this->service->searchPod($trackingNumber);

        // THEN.
        self::assertInstanceOf(ProofOfDelivery::class, $result);
        self::assertTrue($result->podPresent);
        self::assertSame('PDF', $result->format);
        self::assertSame(base64_encode('fake-pdf-content'), $result->podData);
        self::assertSame(1, $result->statusCode);
    }

    public function testGivenNoPodWhenSearchPodThenReturnsAbsentProof(): void
    {
        // GIVEN.
        $trackingNumber = new TrackingNumber('AB123456789CD');
        $soapResult = new ResultSearchPOD(
            errorCode: 0,
            errorMessage: '',
            podPresente: false,
            statusCode: 0,
        );
        $soapResponse = new SearchPODResponse(return: $soapResult);

        $this->searchServiceMock
            ->method('searchPOD')
            ->willReturn($soapResponse);

        // WHEN.
        $result = $this->service->searchPod($trackingNumber);

        // THEN.
        self::assertFalse($result->podPresent);
        self::assertNull($result->podData);
    }

    public function testGivenApiFailureWhenSearchPodThenThrowsApiError(): void
    {
        // GIVEN.
        $trackingNumber = new TrackingNumber('AB123456789CD');

        $this->searchServiceMock
            ->method('searchPOD')
            ->willReturn(false);

        $this->searchServiceMock
            ->method('getLastErrorForMethod')
            ->willReturn(new \SoapFault('Server', 'SOAP error'));

        // WHEN / THEN.
        $this->expectException(ApiError::class);
        $this->service->searchPod($trackingNumber);
    }

    public function testGivenErrorCodeWhenSearchPodThenThrowsTrackingException(): void
    {
        // GIVEN.
        $trackingNumber = new TrackingNumber('AB123456789CD');
        $soapResult = new ResultSearchPOD(
            errorCode: 1,
            errorMessage: 'POD search failed',
        );
        $soapResponse = new SearchPODResponse(return: $soapResult);

        $this->searchServiceMock
            ->method('searchPOD')
            ->willReturn($soapResponse);

        // WHEN / THEN.
        $this->expectException(TrackingException::class);
        $this->expectExceptionMessage('POD search failed');
        $this->service->searchPod($trackingNumber);
    }

    public function testGivenSenderRefWhenSearchPodThenReturnsParcelsWithProof(): void
    {
        // GIVEN.
        $parcelPod1 = new ParcelPOD(
            skybillNumber: 'AB123456789CD',
            formatPOD: 'PDF',
            pod: base64_encode('pdf-data-1'),
            podPresente: true,
            statusCode: 1,
        );
        $parcelPod2 = new ParcelPOD(
            skybillNumber: 'EF987654321GH',
            formatPOD: 'PDF',
            pod: base64_encode('pdf-data-2'),
            podPresente: true,
            statusCode: 1,
        );

        $soapResult = new ResultSearchPODWithSenderRef(
            errorCode: 0,
            errorMessage: '',
            listParcelPOD: [$parcelPod1, $parcelPod2],
        );
        $soapResponse = new SearchPODWithSenderRefResponse(return: $soapResult);

        $this->searchServiceMock
            ->method('searchPODWithSenderRef')
            ->willReturn($soapResponse);

        // WHEN.
        $result = $this->service->searchPodWithSenderRef(
            'MY-SENDER-REF',
        );

        // THEN.
        self::assertInstanceOf(ProofOfDeliveryByRef::class, $result);
        self::assertCount(2, $result->parcels);
        self::assertSame('AB123456789CD', $result->parcels[0]->skybillNumber);
        self::assertTrue($result->parcels[0]->podPresent);
        self::assertSame('EF987654321GH', $result->parcels[1]->skybillNumber);
    }

    public function testGivenApiFailureWhenSearchPodByRefThenThrowsApiError(): void
    {
        // GIVEN.
        $this->searchServiceMock
            ->method('searchPODWithSenderRef')
            ->willReturn(false);

        $this->searchServiceMock
            ->method('getLastErrorForMethod')
            ->willReturn(new \SoapFault('Server', 'SOAP error'));

        // WHEN / THEN.
        $this->expectException(ApiError::class);
        $this->service->searchPodWithSenderRef('REF');
    }

    public function testGivenErrorCodeWhenSearchPodByRefThenThrowsTrackingException(): void
    {
        // GIVEN.
        $soapResult = new ResultSearchPODWithSenderRef(
            errorCode: 2,
            errorMessage: 'Sender ref not found',
        );
        $soapResponse = new SearchPODWithSenderRefResponse(return: $soapResult);

        $this->searchServiceMock
            ->method('searchPODWithSenderRef')
            ->willReturn($soapResponse);

        // WHEN / THEN.
        $this->expectException(TrackingException::class);
        $this->expectExceptionMessage('Sender ref not found');
        $this->service->searchPodWithSenderRef('REF');
    }
}
