<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\Services\Tracking;

use ChronopostTracking\ServiceType\Cancel;
use ChronopostTracking\StructType\CancelListSkybillResponse;
use ChronopostTracking\StructType\CancelSkybillResponse;
use ChronopostTracking\StructType\ResultCancelSkybill;
use ChronopostTracking\StructType\ResultListCancelSkybill;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\CancelResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\CancelListResult;
use Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError;
use Kwaadpepper\ChronopostApiPhp\Exceptions\Tracking\TrackingException;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingNumber;
use Kwaadpepper\ChronopostApiPhp\Services\Tracking\TrackCancelService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 * @phpcs:disable Generic.Formatting.MultipleStatementAlignment.NotSameWarning
 */
class TrackCancelServiceTest extends TestCase
{
    private Cancel&MockObject $cancelServiceMock;

    private TrackCancelService $service;

    private AccountNumber $accountNumber;

    private Password $password;

    protected function setUp(): void
    {
        $this->cancelServiceMock = $this->createMock(Cancel::class);
        $this->accountNumber = new AccountNumber('19869502');
        $this->password = new Password('255562');

        $this->service = new TrackCancelService(cancelService: $this->cancelServiceMock);
    }

    public function testGivenValidTrackingNumberWhenCancelThenReturnsCancelResult(): void
    {
        // GIVEN.
        $trackingNumber = new TrackingNumber('AB123456789CD');
        $soapResult = new ResultCancelSkybill(
            errorCode: 0,
            errorMessage: 'Success',
            statusCode: 1,
        );
        $soapResponse = new CancelSkybillResponse(return: $soapResult);

        $this->cancelServiceMock
            ->method('cancelSkybill')
            ->willReturn($soapResponse);

        // WHEN.
        $result = $this->service->cancelSkybill(
            $this->accountNumber,
            $this->password,
            $trackingNumber,
        );

        // THEN.
        self::assertInstanceOf(CancelResult::class, $result);
        self::assertSame(0, $result->errorCode);
        self::assertSame('Success', $result->errorMessage);
        self::assertSame(1, $result->statusCode);
    }

    public function testGivenApiErrorWhenCancelThenThrowsApiError(): void
    {
        // GIVEN.
        $trackingNumber = new TrackingNumber('AB123456789CD');

        $this->cancelServiceMock
            ->method('cancelSkybill')
            ->willReturn(false);

        $this->cancelServiceMock
            ->method('getLastErrorForMethod')
            ->willReturn(new \SoapFault('Server', 'SOAP error'));

        // WHEN / THEN.
        $this->expectException(ApiError::class);
        $this->service->cancelSkybill($this->accountNumber, $this->password, $trackingNumber);
    }

    public function testGivenNullResponseWhenCancelThenThrowsApiError(): void
    {
        // GIVEN.
        $trackingNumber = new TrackingNumber('AB123456789CD');
        $soapResponse = new CancelSkybillResponse(return: null);

        $this->cancelServiceMock
            ->method('cancelSkybill')
            ->willReturn($soapResponse);

        // WHEN / THEN.
        $this->expectException(ApiError::class);
        $this->service->cancelSkybill($this->accountNumber, $this->password, $trackingNumber);
    }

    public function testGivenErrorCodeWhenCancelThenThrowsTrackingException(): void
    {
        // GIVEN.
        $trackingNumber = new TrackingNumber('AB123456789CD');
        $soapResult = new ResultCancelSkybill(
            errorCode: 1,
            errorMessage: 'Skybill not found',
            statusCode: 0,
        );
        $soapResponse = new CancelSkybillResponse(return: $soapResult);

        $this->cancelServiceMock
            ->method('cancelSkybill')
            ->willReturn($soapResponse);

        // WHEN / THEN.
        $this->expectException(TrackingException::class);
        $this->expectExceptionMessage('Skybill not found');
        $this->service->cancelSkybill($this->accountNumber, $this->password, $trackingNumber);
    }

    public function testGivenMultipleNumbersWhenCancelListThenReturnsCancelListResult(): void
    {
        // GIVEN.
        $trackingNumbers = [
            new TrackingNumber('AB123456789CD'),
            new TrackingNumber('EF987654321GH'),
        ];
        $soapResult = new ResultListCancelSkybill(
            errorCode: 0,
            errorMessage: 'Success',
            statusCode: 1,
            skybills: ['AB123456789CD', 'EF987654321GH'],
        );
        $soapResponse = new CancelListSkybillResponse(return: $soapResult);

        $this->cancelServiceMock
            ->method('cancelListSkybill')
            ->willReturn($soapResponse);

        // WHEN.
        $result = $this->service->cancelListSkybill(
            $this->accountNumber,
            $this->password,
            $trackingNumbers,
        );

        // THEN.
        self::assertInstanceOf(CancelListResult::class, $result);
        self::assertSame(0, $result->errorCode);
        self::assertSame('Success', $result->errorMessage);
        self::assertSame(1, $result->statusCode);
        self::assertSame(['AB123456789CD', 'EF987654321GH'], $result->skybills);
    }

    public function testGivenApiErrorWhenCancelListThenThrowsApiError(): void
    {
        // GIVEN.
        $trackingNumbers = [new TrackingNumber('AB123456789CD')];

        $this->cancelServiceMock
            ->method('cancelListSkybill')
            ->willReturn(false);

        $this->cancelServiceMock
            ->method('getLastErrorForMethod')
            ->willReturn(new \SoapFault('Server', 'SOAP error'));

        // WHEN / THEN.
        $this->expectException(ApiError::class);
        $this->service->cancelListSkybill($this->accountNumber, $this->password, $trackingNumbers);
    }

    public function testGivenErrorCodeWhenCancelListThenThrowsTrackingException(): void
    {
        // GIVEN.
        $trackingNumbers = [new TrackingNumber('AB123456789CD')];
        $soapResult = new ResultListCancelSkybill(
            errorCode: 2,
            errorMessage: 'Batch cancel failed',
            statusCode: 0,
        );
        $soapResponse = new CancelListSkybillResponse(return: $soapResult);

        $this->cancelServiceMock
            ->method('cancelListSkybill')
            ->willReturn($soapResponse);

        // WHEN / THEN.
        $this->expectException(TrackingException::class);
        $this->expectExceptionMessage('Batch cancel failed');
        $this->service->cancelListSkybill($this->accountNumber, $this->password, $trackingNumbers);
    }
}
