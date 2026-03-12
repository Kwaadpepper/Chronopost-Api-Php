<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\ObjectValues\Pickup;

use Kwaadpepper\ChronopostApiPhp\Enums\Civility;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Email;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PersonName;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\PickupShipper;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class PickupShipperTest extends TestCase
{
    public function testCanInstantiateWithAllFields(): void
    {
        // GIVEN.
        $contactName = new PersonName('Jean Dupont');
        $email       = new Email('jean@example.com');

        // WHEN.
        $shipper = new PickupShipper(
            address1: '1 rue de la Paix',
            address2: 'Bât A',
            city: 'Paris',
            civility: Civility::MR,
            contactName: $contactName,
            country: 'FR',
            countryName: 'France',
            email: $email,
            mobilePhone: '0612345678',
            name: 'Dupont SARL',
            name2: 'Service Expédition',
            phone: '0112345678',
            preAlert: 1,
            zipCode: '75001',
        );

        // THEN.
        $this->assertSame('1 rue de la Paix', $shipper->getAddress1());
        $this->assertSame('Bât A', $shipper->getAddress2());
        $this->assertSame('Paris', $shipper->getCity());
        $this->assertSame(Civility::MR, $shipper->getCivility());
        $this->assertSame($contactName, $shipper->getContactName());
        $this->assertSame('FR', $shipper->getCountry());
        $this->assertSame('France', $shipper->getCountryName());
        $this->assertSame($email, $shipper->getEmail());
        $this->assertSame('0612345678', $shipper->getMobilePhone());
        $this->assertSame('Dupont SARL', $shipper->getName());
        $this->assertSame('Service Expédition', $shipper->getName2());
        $this->assertSame('0112345678', $shipper->getPhone());
        $this->assertSame(1, $shipper->getPreAlert());
        $this->assertSame('75001', $shipper->getZipCode());
    }

    public function testCanInstantiateWithDefaults(): void
    {
        // WHEN.
        $shipper = new PickupShipper();

        // THEN.
        $this->assertNull($shipper->getAddress1());
        $this->assertNull($shipper->getCity());
        $this->assertNull($shipper->getCivility());
        $this->assertNull($shipper->getContactName());
        $this->assertNull($shipper->getEmail());
        $this->assertNull($shipper->getZipCode());
    }
}
