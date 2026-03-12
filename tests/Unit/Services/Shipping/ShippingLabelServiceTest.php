<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\Services\Shipping;

use ChronopostShipping\ServiceType\Get;
use ChronopostShipping\StructType\Error;
use ChronopostShipping\StructType\GeopostResult;
use ChronopostShipping\StructType\GetReservedSkybillResponse;
use ChronopostShipping\StructType\GetRoutingResponse;
use ChronopostShipping\StructType\GetShippingInformationResponse;
use ChronopostShipping\StructType\GetSkybillResponse;
use ChronopostShipping\StructType\RecipientValue;
use ChronopostShipping\StructType\ResultGetReservedSkybillValue;
use ChronopostShipping\StructType\ResultGetRouting;
use ChronopostShipping\StructType\ResultShippingInfo;
use ChronopostShipping\StructType\ResultGetReservedSkybillWithTypeValue;
use ChronopostShipping\StructType\ShippingInfo;
use ChronopostShipping\StructType\ShipperValue;
use ChronopostShipping\StructType\SkybillValueBase;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\RoutingInfo;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\ShippingInformation;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\SkybillLabel;
use Kwaadpepper\ChronopostApiPhp\Enums\CountryForChronopost;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\RoutingQuery;
use Kwaadpepper\ChronopostApiPhp\Services\Shipping\ShippingLabelService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class ShippingLabelServiceTest extends TestCase
{
    private Get&MockObject $getMock;

    private ShippingLabelService $service;

    private AccountNumber $accountNumber;

    private Password $password;

    protected function setUp(): void
    {
        $this->getMock = $this->createMock(Get::class);
        $this->accountNumber = new AccountNumber('19869502');
        $this->password      = new Password('255562');

        $this->service = new ShippingLabelService(
            accountNumber: $this->accountNumber,
            password: $this->password,
            getService: $this->getMock,
        );
    }

    public function testGivenSkybillNumberWhenGetLabelThenReturnsTransportTicket(): void
    {
        // GIVEN.
        $pdfBase64 = base64_encode('fake-label');
        $result    = new ResultGetReservedSkybillWithTypeValue(errorCode: 0, errorMessage: '', skybill: $pdfBase64);

        $this->getMock
            ->method('getSkybill')
            ->willReturn(new GetSkybillResponse($result));

        // WHEN.
        $label = $this->service->getSkybill(
            'SKY123456',
        );

        // THEN.
        self::assertInstanceOf(SkybillLabel::class, $label);
        self::assertSame('SKY123456', $label->skybillNumber);
        self::assertSame($pdfBase64, $label->transportTicket->base64);
    }

    public function testGivenReservationNumberWhenGetReservedLabelThenReturnsTicket(): void
    {
        // GIVEN.
        $pdfBase64 = base64_encode('reserved-label');
        $result    = new ResultGetReservedSkybillValue(errorCode: 0, errorMessage: '', skybill: $pdfBase64);

        $this->getMock
            ->method('getReservedSkybill')
            ->willReturn(new GetReservedSkybillResponse($result));

        // WHEN.
        $label = $this->service->getReservedSkybill(
            'RES123',
        );

        // THEN.
        self::assertInstanceOf(SkybillLabel::class, $label);
        self::assertSame('RES123', $label->skybillNumber);
        self::assertSame($pdfBase64, $label->transportTicket->base64);
    }

    public function testGivenSkybillNumberWhenGetRoutingThenReturnsRoutingInfo(): void
    {
        // GIVEN.
        $geopostResult = new GeopostResult();
        $geopostResult->setDSort('DS01');
        $geopostResult->setOSort('OS01');
        $geopostResult->setServiceMark('SM01');

        $result = new ResultGetRouting(
            errorCode: 0,
            errorMessage: '',
            geopostResult: $geopostResult,
            posteComptable: 'PC001',
        );

        $this->getMock
            ->method('getRouting')
            ->willReturn(new GetRoutingResponse($result));

        // WHEN.
        $routing = $this->service->getRouting(
            new RoutingQuery('DEP01', new PostCode('75001', CountryForChronopost::FRANCE)),
        );

        // THEN.
        self::assertInstanceOf(RoutingInfo::class, $routing);
        self::assertSame('PC001', $routing->posteComptable);
        self::assertSame('DS01', $routing->geopostData['dSort']);
        self::assertSame('OS01', $routing->geopostData['oSort']);
        self::assertSame('SM01', $routing->geopostData['serviceMark']);
    }

    public function testGivenShippingContextWhenGetShippingInfoThenReturnsInfo(): void
    {
        // GIVEN.
        $shippingInfo = new ShippingInfo(
            asCode: 'AS01',
            codeService: 'SVC01',
            destinationDepot: 'DEP01',
            groupingPriorityLabel: 'PRIO',
            serviceMark: 'SM01',
            serviceName: 'Chrono 13',
            signaletiqueProduit: 'SIGNAL',
            dSort: 'DS01',
            oSort: 'OS01',
        );

        $result = new ResultShippingInfo(
            error: new Error(errorCode: 0, errorMessage: ''),
            shippingInfo: $shippingInfo,
        );

        $this->getMock
            ->method('getShippingInformation')
            ->willReturn(new GetShippingInformationResponse($result));

        // WHEN.
        $info = $this->service->getShippingInformation(
            new ShipperValue(),
            new RecipientValue(),
            new SkybillValueBase(),
        );

        // THEN.
        self::assertInstanceOf(ShippingInformation::class, $info);
        self::assertSame('AS01', $info->asCode);
        self::assertSame('SVC01', $info->codeService);
        self::assertSame('DEP01', $info->destinationDepot);
        self::assertSame('Chrono 13', $info->serviceName);
    }
}
