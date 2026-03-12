<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\ObjectValues\Pickup;

use Kwaadpepper\ChronopostApiPhp\ObjectValues\Email;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PersonName;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\DpdRecipientAddress;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class DpdRecipientAddressTest extends TestCase
{
    public function testCanInstantiateWithAllFields(): void
    {
        // GIVEN.
        $lastName  = new PersonName('Schmidt');
        $firstName = new PersonName('Hans');
        $email     = new Email('hans@example.de');

        // WHEN.
        $addr = new DpdRecipientAddress(
            address: '1 Berliner Str',
            addressLine2: 'Apt 5',
            countryCode: 'DE',
            postalCode: '10115',
            digicode: 'B456',
            floor: '2',
            email: $email,
            lastName: $lastName,
            weight: 3.5,
            firstName: $firstName,
            companyName: 'Schmidt GmbH',
            recipientReference: 'REF-DE-001',
            phone: '+4930123456',
            city: 'Berlin',
        );

        // THEN.
        $this->assertSame('1 Berliner Str', $addr->getAddress());
        $this->assertSame('Apt 5', $addr->getAddressLine2());
        $this->assertSame('DE', $addr->getCountryCode());
        $this->assertSame('10115', $addr->getPostalCode());
        $this->assertSame('B456', $addr->getDigicode());
        $this->assertSame('2', $addr->getFloor());
        $this->assertSame($email, $addr->getEmail());
        $this->assertSame($lastName, $addr->getLastName());
        $this->assertSame(3.5, $addr->getWeight());
        $this->assertSame($firstName, $addr->getFirstName());
        $this->assertSame('Schmidt GmbH', $addr->getCompanyName());
        $this->assertSame('REF-DE-001', $addr->getRecipientReference());
        $this->assertSame('+4930123456', $addr->getPhone());
        $this->assertSame('Berlin', $addr->getCity());
    }

    public function testCanInstantiateWithDefaults(): void
    {
        // WHEN.
        $addr = new DpdRecipientAddress();

        // THEN.
        $this->assertNull($addr->getAddress());
        $this->assertNull($addr->getCountryCode());
        $this->assertNull($addr->getEmail());
        $this->assertNull($addr->getWeight());
        $this->assertNull($addr->getCity());
    }
}
