<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Feature;

use Kwaadpepper\ChronopostApiPhp\ChronopostApi;
use Kwaadpepper\ChronopostApiPhp\Enums\ChronopostProductCode;
use Kwaadpepper\ChronopostApiPhp\Enums\Civility;
use Kwaadpepper\ChronopostApiPhp\Enums\CountryForChronopost;
use Kwaadpepper\ChronopostApiPhp\Enums\DeliveryServiceCode;
use Kwaadpepper\ChronopostApiPhp\Enums\ParcelInfoType;
use Kwaadpepper\ChronopostApiPhp\Enums\ShippingType;
use Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingException;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\CustomerValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\RecipientValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ReferenceValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ShipperValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\SkyBillValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PhoneNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ProductCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\RoutingQuery;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ServiceCode;
use PHPUnit\Framework\TestCase;

/**
 * Feature tests for ShippingFacade — real SOAP calls via ChronopostApi.
 *
 * @group integration
 *
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class ShippingFacadeTest extends TestCase
{
    private ChronopostApi $api;

    protected function setUp(): void
    {
        $this->api = new ChronopostApi(
            new AccountNumber('19869502'),
            new Password('255562'),
        );
    }

    /**
     * Highway test: create a single Chrono 13 parcel through the facade.
     */
    public function testSingleParcelV4Chrono13(): void
    {
        $skybillValue = new SkyBillValue(
            ShippingType::MERCHANDISE,
            ProductCode::fromEnum(ChronopostProductCode::CHRONO_13),
            ServiceCode::fromEnum(DeliveryServiceCode::DELIVERY_ON_MONDAY),
            2.5,
        );

        $customerValue = new CustomerValue(
            Civility::MR,
            'Jean Dupont',
            'jean.dupont@example.com',
            '10 Rue de Rivoli',
            null,
            'Paris',
            new PostCode('75001', CountryForChronopost::FRANCE),
            new PhoneNumber('0601020304', CountryForChronopost::FRANCE),
        );

        $shipperValue = new ShipperValue(
            Civility::MME,
            'Marie Martin',
            'marie.martin@example.com',
            '5 Avenue Bollée',
            null,
            'Le Mans',
            new PostCode('72000', CountryForChronopost::FRANCE),
            ParcelInfoType::COMPANY,
            new PhoneNumber('0605060708', CountryForChronopost::FRANCE),
        );

        $recipientValue = new RecipientValue(
            Civility::MR,
            'Pierre Durand',
            'pierre.durand@example.com',
            '20 Rue de la Paix',
            null,
            'Lyon',
            new PostCode('69001', CountryForChronopost::FRANCE),
            ParcelInfoType::INDIVIDUAL,
            new PhoneNumber('0612131415', CountryForChronopost::FRANCE),
        );

        $result     = $this->api->shipping->singleParcelV4(
            $skybillValue,
            $customerValue,
            $shipperValue,
            $recipientValue,
            new ReferenceValue(),
        );
        $firstValue = $result->multiParcelValue[0] ?? null;

        $this->assertCount(1, $result->multiParcelValue);
        $this->assertNotNull($firstValue);
        $this->assertNotEmpty($firstValue->transportTicket->base64);
    }

    /**
     * getRouting requires valid shipperDepot (7 digits) and socode (3 digits) from Chronopost.
     * With dummy values, a ShippingException is expected, confirming the SOAP call + validation work.
     */
    public function testGetRoutingThrowsWithInvalidDepot(): void
    {
        $this->expectException(ShippingException::class);

        $query = new RoutingQuery(
            '0000086',
            new PostCode('75001', CountryForChronopost::FRANCE),
            '000',
        );

        $this->api->shipping->getRouting($query);
    }
}
