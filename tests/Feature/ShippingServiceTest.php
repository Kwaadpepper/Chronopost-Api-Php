<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Feature;

use Kwaadpepper\ChronopostApiPhp\Enums\ChronopostProductCode;
use Kwaadpepper\ChronopostApiPhp\Enums\Civility;
use Kwaadpepper\ChronopostApiPhp\Enums\CountryForChronopost;
use Kwaadpepper\ChronopostApiPhp\Enums\DeliveryServiceCode;
use Kwaadpepper\ChronopostApiPhp\Enums\ParcelInfoType;
use Kwaadpepper\ChronopostApiPhp\Enums\ShippingType;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\CustomerValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ParcelContent;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\RecipientValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ReferenceValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ShipperValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\SkyBillValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PhoneNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ProductCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ServiceCode;
use Kwaadpepper\ChronopostApiPhp\Services\Shipping\ShippingService;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class ShippingServiceTest extends \PHPUnit\Framework\TestCase
{
    public function testCanInstantiateShippingService(): void
    {
        // WHEN.
        new ShippingService(
            new AccountNumber('19869502'),
            new Password('255562'),
        );

        // THEN.
        $this->expectNotToPerformAssertions();
    }

    public function testCanGetCreateSingleParcel(): void
    {
        // GIVEN.
        $accountNumber  = new AccountNumber('19869502');
        $password       = new Password('255562');
        $parcelContent  = new ParcelContent(
            'Jelly bears of dirretent colors red, green, yellow, blue and orange',
        );
        $referenceValue = new ReferenceValue();
        $skybillValue   = new SkyBillValue(
            ShippingType::MERCHANDISE,
            ProductCode::fromEnum(ChronopostProductCode::CHRONO_13),
            ServiceCode::fromEnum(DeliveryServiceCode::DELIVERY_ON_MONDAY),
            25.23,
        );
        $customerValue  = new CustomerValue(
            Civility::MR,
            'John Doe customer',
            'john.doe@gmail.com',
            '123 Main Street',
            null,
            'Paris',
            new PostCode(
                '75001',
                CountryForChronopost::FRANCE,
            ),
            new PhoneNumber(
                '0601020304',
                CountryForChronopost::FRANCE,
            ),
        );
        $shipperValue   = new ShipperValue(
            Civility::MME,
            'Jane Doe shipper',
            'jane.doe@gmail.com',
            '456 Another Street',
            null,
            'Lyon',
            new PostCode(
                '69001',
                CountryForChronopost::FRANCE,
            ),
            ParcelInfoType::COMPANY,
            new PhoneNumber(
                '0605060708',
                CountryForChronopost::FRANCE,
            ),
        );
        $recipientValue = new RecipientValue(
            Civility::MR,
            'Jack Smith recipient',
            'jack.smith@gmail.com',
            '789 Third Street',
            null,
            'Marseille',
            new PostCode(
                '13001',
                CountryForChronopost::FRANCE,
            ),
            ParcelInfoType::INDIVIDUAL,
            new PhoneNumber(
                '0612131415',
                CountryForChronopost::FRANCE,
            ),
        );

        $shippingService = new ShippingService($accountNumber, $password);

        // WHEN.
        $result           = $shippingService->singleParcelV4(
            $skybillValue,
            $customerValue,
            $shipperValue,
            $recipientValue,
            $referenceValue,
        );
        $firstParcelValue = $result->multiParcelValue[0] ?? null;

        // THEN.
        $this->assertCount(1, $result->multiParcelValue);
        $this->assertNotNull($firstParcelValue);
        $this->assertNotEmpty($firstParcelValue->transportTicket->base64);
    }
}
