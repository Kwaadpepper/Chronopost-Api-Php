<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\ObjectValues\Pickup;

use Kwaadpepper\ChronopostApiPhp\Enums\Civility;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Email;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PersonName;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\PickupAddress;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\SenderReference;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class PickupAddressTest extends TestCase
{
    public function testCanInstantiateWithAllFields(): void
    {
        // GIVEN.
        $lastName    = new PersonName('Dupont');
        $contactName = new PersonName('Jean Dupont');
        $firstName   = new PersonName('Jean');
        $email       = new Email('jean@example.com');
        $senderRef   = new SenderReference('REF001');

        // WHEN.
        $addr = new PickupAddress(
            civility: Civility::MR,
            countryCode: 'FR',
            doorCode: 'A1234',
            postalCode: '72000',
            hamlet: 'Le Bourg',
            lastName: $lastName,
            contactName: $contactName,
            streetNumber: '42',
            doorToDoor: true,
            firstName: $firstName,
            companyName: 'Dupont SARL',
            buildingFloor: '3ème étage',
            serviceDirection: 'Expéditions',
            phone: '0243000001',
            city: 'Le Mans',
            email: $email,
            senderReference: $senderRef,
        );

        // THEN.
        $this->assertSame(Civility::MR, $addr->getCivility());
        $this->assertSame('FR', $addr->getCountryCode());
        $this->assertSame('A1234', $addr->getDoorCode());
        $this->assertSame('72000', $addr->getPostalCode());
        $this->assertSame('Le Bourg', $addr->getHamlet());
        $this->assertSame($lastName, $addr->getLastName());
        $this->assertSame($contactName, $addr->getContactName());
        $this->assertSame('42', $addr->getStreetNumber());
        $this->assertTrue($addr->getDoorToDoor());
        $this->assertSame($firstName, $addr->getFirstName());
        $this->assertSame('Dupont SARL', $addr->getCompanyName());
        $this->assertSame('3ème étage', $addr->getBuildingFloor());
        $this->assertSame('Expéditions', $addr->getServiceDirection());
        $this->assertSame('0243000001', $addr->getPhone());
        $this->assertSame('Le Mans', $addr->getCity());
        $this->assertSame($email, $addr->getEmail());
        $this->assertSame($senderRef, $addr->getSenderReference());
    }

    public function testCanInstantiateWithDefaults(): void
    {
        // WHEN.
        $addr = new PickupAddress();

        // THEN.
        $this->assertNull($addr->getCivility());
        $this->assertNull($addr->getCountryCode());
        $this->assertNull($addr->getPostalCode());
        $this->assertNull($addr->getCity());
        $this->assertNull($addr->getSenderReference());
    }
}
