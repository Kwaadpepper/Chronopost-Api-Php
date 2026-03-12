<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\Services\Shipping;

use ChronopostShipping\ServiceType\Shipping;
use ChronopostShipping\StructType\ResultMonoParcelExpeditionValue;
use ChronopostShipping\StructType\ResultMultiParcelExpeditionValue;
use ChronopostShipping\StructType\ResultMultiParcelValue;
use ChronopostShipping\StructType\ResultReservationExpeditionValue;
use ChronopostShipping\StructType\ResultReservationExpeditionValueV2;
use ChronopostShipping\StructType\ResultReservationMultiParcelExpeditionValueV2;
use ChronopostShipping\StructType\ResultParcelValue;
use ChronopostShipping\StructType\ShippingMultiParcelV7Response;
use ChronopostShipping\StructType\ShippingV7Response;
use ChronopostShipping\StructType\ShippingWithESDOnlyV2Response;
use ChronopostShipping\StructType\ShippingWithReservationAndESDWithRefClientPCResponse;
use ChronopostShipping\StructType\ShippingWithReservationV2Response;
use ChronopostShipping\StructType\ShippingMultiParcelWithReservationV3Response;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\MonoParcelV7;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\MultiParcelV4;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\ReservationResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\ReservationMultiParcelResult;
use Kwaadpepper\ChronopostApiPhp\Enums\Civility;
use Kwaadpepper\ChronopostApiPhp\Enums\ChronopostProductCode;
use Kwaadpepper\ChronopostApiPhp\Enums\CountryForChronopost;
use Kwaadpepper\ChronopostApiPhp\Enums\DeliveryServiceCode;
use Kwaadpepper\ChronopostApiPhp\Enums\ParcelInfoType;
use Kwaadpepper\ChronopostApiPhp\Enums\ShippingType;
use Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError;
use Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\CustomerValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\EsdValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\RecipientValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ReferenceValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ShipperValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\SkyBillValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ProductCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ServiceCode;
use Kwaadpepper\ChronopostApiPhp\Services\Shipping\ShippingService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 * @phpcs:disable Generic.Formatting.MultipleStatementAlignment.NotSameWarning
 */
class ShippingServiceV7Test extends TestCase
{
    private Shipping&MockObject $shippingMock;

    private ShippingService $service;

    private AccountNumber $accountNumber;

    private Password $password;

    private SkyBillValue $skybillValue;

    private CustomerValue $customerValue;

    private ShipperValue $shipperValue;

    private RecipientValue $recipientValue;

    private ReferenceValue $referenceValue;

    protected function setUp(): void
    {
        $this->shippingMock = $this->createMock(Shipping::class);
        $this->accountNumber = new AccountNumber('19869502');
        $this->password = new Password('255562');
        $this->skybillValue = new SkyBillValue(
            ShippingType::MERCHANDISE,
            ProductCode::fromEnum(ChronopostProductCode::CHRONO_13),
            ServiceCode::fromEnum(DeliveryServiceCode::DELIVERY_ON_MONDAY),
            2.5,
        );
        $this->customerValue = new CustomerValue(
            Civility::MR,
            'Customer Name',
            'customer@example.com',
            '1 rue de la Paix',
            null,
            'Paris',
            new PostCode('75001', CountryForChronopost::FRANCE),
            null,
        );
        $this->shipperValue = new ShipperValue(
            Civility::MR,
            'Shipper Name',
            'shipper@example.com',
            '1 rue de Rivoli',
            null,
            'Paris',
            new PostCode('75001', CountryForChronopost::FRANCE),
            ParcelInfoType::COMPANY,
            null,
        );
        $this->recipientValue = new RecipientValue(
            Civility::MR,
            'Recipient Name',
            'recipient@example.com',
            '10 rue du Commerce',
            null,
            'Lyon',
            new PostCode('69001', CountryForChronopost::FRANCE),
            ParcelInfoType::COMPANY,
            null,
        );
        $this->referenceValue = new ReferenceValue();

        $this->service = new ShippingService(shippingService: $this->shippingMock);
    }

    public function testGivenSingleParcelWhenShipV7ThenReturnsMonoParcelResult(): void
    {
        // GIVEN.
        $pdfBase64 = base64_encode('fake-pdf-content');
        $monoResult = new ResultMonoParcelExpeditionValue($pdfBase64);
        $monoResult->setCodeDepot('DEP01');
        $monoResult->setCodeService('SVC01');
        $monoResult->setDestinationDepot('DEST01');
        $monoResult->setGeoPostCodeBarre('BARCODE123');
        $monoResult->setGeoPostNumeroColis('COLIS123');
        $monoResult->setGroupingPriorityLabel('PRIORITY');
        $monoResult->setServiceMark('MARK');
        $monoResult->setServiceName('Chrono 13');
        $monoResult->setSignaletiqueProduit('SIGNAL');
        $monoResult->setSkybillNumber('SKY123456');
        $monoResult->setDSort('DS');
        $monoResult->setOSort('OS');
        $monoResult->setErrorCode(0);
        $monoResult->setErrorMessage('');

        $response = new ShippingV7Response($monoResult);

        $this->shippingMock
            ->method('shippingV7')
            ->willReturn($response);

        // WHEN.
        $result = $this->service->singleParcelV7(
            $this->accountNumber,
            $this->password,
            $this->skybillValue,
            $this->customerValue,
            $this->shipperValue,
            $this->recipientValue,
            $this->referenceValue,
        );

        // THEN.
        self::assertInstanceOf(MonoParcelV7::class, $result);
        self::assertSame('SKY123456', $result->skybillNumber);
        self::assertSame($pdfBase64, $result->transportTicket->base64);
        self::assertSame('DEP01', $result->codeDepot);
        self::assertSame('SVC01', $result->codeService);
        self::assertSame('DEST01', $result->destinationDepot);
        self::assertSame('BARCODE123', $result->geoPostCodeBarre);
        self::assertSame('COLIS123', $result->geoPostNumeroColis);
        self::assertSame('PRIORITY', $result->groupingPriorityLabel);
        self::assertSame('MARK', $result->serviceMark);
        self::assertSame('Chrono 13', $result->serviceName);
        self::assertSame('SIGNAL', $result->signaletiqueProduit);
        self::assertNull($result->esdInfo);
    }

    public function testGivenSingleParcelWithEsdWhenShipV7ThenReturnsEsdInfo(): void
    {
        // GIVEN.
        $pdfBase64 = base64_encode('fake-pdf-content');
        $monoResult = new ResultMonoParcelExpeditionValue($pdfBase64);
        $monoResult->setErrorCode(0);
        $monoResult->setErrorMessage('');
        $monoResult->setSkybillNumber('SKY999');
        $monoResult->setESDFullNumber('ESD-FULL-001');
        $monoResult->setESDNumber('ESD-001');
        $monoResult->setPickupDate('2025-01-15T10:00:00');

        $response = new ShippingV7Response($monoResult);

        $this->shippingMock
            ->method('shippingV7')
            ->willReturn($response);

        $esdValue = new EsdValue(
            new \DateTimeImmutable('2025-01-15T18:00:00'),
            new \DateTimeImmutable('2025-01-15T10:00:00'),
            'Floor2',
            'CC01',
            'DirNord',
            'InstructionsSpec',
            'REFCLIENT01',
        );

        // WHEN.
        $result = $this->service->singleParcelV7(
            $this->accountNumber,
            $this->password,
            $this->skybillValue,
            $this->customerValue,
            $this->shipperValue,
            $this->recipientValue,
            $this->referenceValue,
            esdValue: $esdValue,
        );

        // THEN.
        self::assertInstanceOf(MonoParcelV7::class, $result);
        self::assertNotNull($result->esdInfo);
        self::assertSame('ESD-FULL-001', $result->esdInfo->fullNumber);
        self::assertSame('ESD-001', $result->esdInfo->number);
    }

    public function testGivenApiFailureWhenShipV7ThenThrowsApiError(): void
    {
        // GIVEN.
        $this->shippingMock
            ->method('shippingV7')
            ->willReturn(false);
        $this->shippingMock
            ->method('getLastErrorForMethod')
            ->willReturn(new \SoapFault('Server', 'SOAP fault'));

        // WHEN / THEN.
        $this->expectException(ApiError::class);

        $this->service->singleParcelV7(
            $this->accountNumber,
            $this->password,
            $this->skybillValue,
            $this->customerValue,
            $this->shipperValue,
            $this->recipientValue,
            $this->referenceValue,
        );
    }

    public function testGivenShippingErrorWhenShipV7ThenThrowsShippingException(): void
    {
        // GIVEN.
        $monoResult = new ResultMonoParcelExpeditionValue();
        $monoResult->setErrorCode(33);
        $monoResult->setErrorMessage('Invalid product code');

        $response = new ShippingV7Response($monoResult);

        $this->shippingMock
            ->method('shippingV7')
            ->willReturn($response);

        // WHEN / THEN.
        $this->expectException(ShippingException::class);

        $this->service->singleParcelV7(
            $this->accountNumber,
            $this->password,
            $this->skybillValue,
            $this->customerValue,
            $this->shipperValue,
            $this->recipientValue,
            $this->referenceValue,
        );
    }

    public function testGivenMultiParcelWhenShipV7ThenReturnsMultiParcelResult(): void
    {
        // GIVEN.
        $pdfBase64 = base64_encode('fake-multi-pdf');
        $parcelResult = new ResultMultiParcelValue();
        $parcelResult->setSkybillNumber('MULTI001');
        $parcelResult->setCodeDepot('DEP01');
        $parcelResult->setCodeService('SVC01');
        $parcelResult->setDestinationDepot('DEST01');
        $parcelResult->setGeoPostCodeBarre('BAR001');
        $parcelResult->setGeoPostNumeroColis('COL001');
        $parcelResult->setGroupingPriorityLabel('PRI');
        $parcelResult->setServiceMark('MARK');
        $parcelResult->setServiceName('Chrono 13');
        $parcelResult->setSignaletiqueProduit('SIG');
        $parcelResult->setPdfEtiquette($pdfBase64);
        $parcelResult->setDSort('DS');
        $parcelResult->setOSort('OS');

        $multiResult = new ResultMultiParcelExpeditionValue(
            resultMultiParcelValue: [$parcelResult],
        );
        $multiResult->setErrorCode(0);
        $multiResult->setErrorMessage('');

        $response = new ShippingMultiParcelV7Response($multiResult);

        $this->shippingMock
            ->method('shippingMultiParcelV7')
            ->willReturn($response);

        // WHEN.
        $result = $this->service->multiParcelV7(
            $this->accountNumber,
            $this->password,
            [$this->skybillValue],
            $this->customerValue,
            [$this->shipperValue],
            [$this->recipientValue],
            [$this->referenceValue],
        );

        // THEN.
        self::assertInstanceOf(MultiParcelV4::class, $result);
        self::assertCount(1, $result->multiParcelValue);
        self::assertSame('MULTI001', $result->multiParcelValue[0]->skybillNumber);
    }

    public function testGivenSingleParcelWithReservationWhenShipThenReturnsReservationResult(): void
    {
        // GIVEN.
        $reservResult = new ResultReservationExpeditionValueV2('AS001');
        $reservResult->setReservationNumber('RESV-001');
        $reservResult->setSkybillNumber('SKY-RESV-001');
        $reservResult->setCodeDepot('DEP01');
        $reservResult->setCodeService('SVC01');
        $reservResult->setDestinationDepot('DEST01');
        $reservResult->setGeoPostCodeBarre('BAR001');
        $reservResult->setGeoPostNumeroColis('COL001');
        $reservResult->setGroupingPriorityLabel('PRI');
        $reservResult->setServiceMark('MARK');
        $reservResult->setServiceName('Chrono 13');
        $reservResult->setSignaletiqueProduit('SIG');
        $reservResult->setErrorCode(0);
        $reservResult->setErrorMessage('');

        $response = new ShippingWithReservationV2Response($reservResult);

        $this->shippingMock
            ->method('shippingWithReservationV2')
            ->willReturn($response);

        // WHEN.
        $result = $this->service->singleParcelWithReservation(
            $this->accountNumber,
            $this->password,
            $this->skybillValue,
            $this->customerValue,
            $this->shipperValue,
            $this->recipientValue,
            $this->referenceValue,
        );

        // THEN.
        self::assertInstanceOf(ReservationResult::class, $result);
        self::assertSame('RESV-001', $result->reservationNumber);
        self::assertSame('SKY-RESV-001', $result->skybillNumber);
        self::assertSame('DEP01', $result->codeDepot);
    }

    public function testGivenMultiParcelWithReservationWhenShipThenReturnsReservationMultiResult(): void
    {
        // GIVEN.
        $parcelResult = new ResultParcelValue(
            codeDepot: 'DEP01',
            codeService: 'SVC01',
            destinationDepot: 'DEST01',
            geoPostCodeBarre: 'BAR001',
            geoPostNumeroColis: 'COL001',
            groupingPriorityLabel: 'PRI',
            serviceMark: 'MARK',
            serviceName: 'Chrono 13',
            signaletiqueProduit: 'SIG',
            skybillNumber: 'SKY-MULTI-001',
        );

        $multiReservResult = new ResultReservationMultiParcelExpeditionValueV2('AS001');
        $multiReservResult->setReservationNumber('RESV-MULTI-001');
        $multiReservResult->setResultParcelValue([$parcelResult]);
        $multiReservResult->setErrorCode(0);
        $multiReservResult->setErrorMessage('');

        $response = new ShippingMultiParcelWithReservationV3Response($multiReservResult);

        $this->shippingMock
            ->method('shippingMultiParcelWithReservationV3')
            ->willReturn($response);

        // WHEN.
        $result = $this->service->multiParcelWithReservation(
            $this->accountNumber,
            $this->password,
            [$this->skybillValue],
            $this->customerValue,
            $this->shipperValue,
            [$this->recipientValue],
            [$this->referenceValue],
        );

        // THEN.
        self::assertInstanceOf(ReservationMultiParcelResult::class, $result);
        self::assertSame('RESV-MULTI-001', $result->reservationNumber);
        self::assertCount(1, $result->parcelValues);
        self::assertSame('SKY-MULTI-001', $result->parcelValues[0]->skybillNumber);
    }

    public function testGivenEsdOnlyWhenShipThenReturnsReservationResult(): void
    {
        // GIVEN.
        $reservResult = new ResultReservationExpeditionValue(
            codeDepot: 'DEP01',
            codeService: 'SVC01',
            errorCode: 0,
            errorMessage: '',
            skybillNumber: 'SKY-ESD-001',
            reservationNumber: 'RESV-ESD-001',
            eSDFullNumber: 'ESD-FULL-001',
            eSDNumber: 'ESD-001',
            pickupDate: '2025-01-15T10:00:00',
        );

        $response = new ShippingWithESDOnlyV2Response($reservResult);

        $this->shippingMock
            ->method('shippingWithESDOnlyV2')
            ->willReturn($response);

        $esdValue = new EsdValue(
            new \DateTimeImmutable('2025-01-15T18:00:00'),
            new \DateTimeImmutable('2025-01-15T10:00:00'),
            'Floor2',
            'CC01',
            'DirNord',
            'InstructionsSpec',
            'REFCLIENT01',
        );

        // WHEN.
        $result = $this->service->shippingWithEsdOnly(
            $this->accountNumber,
            $this->password,
            $this->skybillValue,
            $this->customerValue,
            $this->shipperValue,
            $this->recipientValue,
            $this->referenceValue,
            $esdValue,
        );

        // THEN.
        self::assertInstanceOf(ReservationResult::class, $result);
        self::assertSame('SKY-ESD-001', $result->skybillNumber);
        self::assertSame('RESV-ESD-001', $result->reservationNumber);
        self::assertNotNull($result->esdInfo);
        self::assertSame('ESD-FULL-001', $result->esdInfo->fullNumber);
    }

    public function testGivenReservationAndEsdWhenShipThenReturnsReservationResult(): void
    {
        // GIVEN.
        $reservResult = new ResultReservationExpeditionValue(
            codeDepot: 'DEP01',
            codeService: 'SVC01',
            errorCode: 0,
            errorMessage: '',
            skybillNumber: 'SKY-COMBO-001',
            reservationNumber: 'RESV-COMBO-001',
            eSDFullNumber: 'ESD-FULL-002',
            eSDNumber: 'ESD-002',
            pickupDate: '2025-02-20T14:00:00',
        );

        $response = new ShippingWithReservationAndESDWithRefClientPCResponse($reservResult);

        $this->shippingMock
            ->method('shippingWithReservationAndESDWithRefClientPC')
            ->willReturn($response);

        $esdValue = new EsdValue(
            new \DateTimeImmutable('2025-02-20T18:00:00'),
            new \DateTimeImmutable('2025-02-20T14:00:00'),
            'Floor3',
            'CC02',
            'DirSud',
            'Fragile',
            'REFCL02',
        );

        // WHEN.
        $result = $this->service->shippingWithReservationAndEsd(
            $this->accountNumber,
            $this->password,
            $this->skybillValue,
            $this->customerValue,
            $this->shipperValue,
            $this->recipientValue,
            $this->referenceValue,
            $esdValue,
        );

        // THEN.
        self::assertInstanceOf(ReservationResult::class, $result);
        self::assertSame('SKY-COMBO-001', $result->skybillNumber);
        self::assertSame('RESV-COMBO-001', $result->reservationNumber);
        self::assertNotNull($result->esdInfo);
        self::assertSame('ESD-FULL-002', $result->esdInfo->fullNumber);
    }

    public function testGivenNullResponseWhenShipV7ThenThrowsApiError(): void
    {
        // GIVEN.
        $response = new ShippingV7Response(null);

        $this->shippingMock
            ->method('shippingV7')
            ->willReturn($response);

        // WHEN / THEN.
        $this->expectException(ApiError::class);

        $this->service->singleParcelV7(
            $this->accountNumber,
            $this->password,
            $this->skybillValue,
            $this->customerValue,
            $this->shipperValue,
            $this->recipientValue,
            $this->referenceValue,
        );
    }
}
