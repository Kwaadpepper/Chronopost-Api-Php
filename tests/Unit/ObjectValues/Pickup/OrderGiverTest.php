<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\ObjectValues\Pickup;

use Kwaadpepper\ChronopostApiPhp\Enums\Civility;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Email;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PersonName;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup\OrderGiver;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class OrderGiverTest extends TestCase
{
    public function testCanInstantiateWithAllFields(): void
    {
        // GIVEN.
        $lastName  = new PersonName('Dupont');
        $firstName = new PersonName('Jean');
        $email     = new Email('jean@example.com');

        // WHEN.
        $og = new OrderGiver(
            address: '10 avenue des Champs',
            building: 'Tour A',
            civility: Civility::MR,
            nafCode: '4791B',
            countryCode: 'FR',
            postalCode: '75008',
            email: $email,
            fax: '0143000000',
            hamlet: 'Les Lilas',
            lastName: $lastName,
            firstName: $firstName,
            companyName: 'Dupont SARL',
            service: 'Logistique',
            phone: '0143000001',
            otherPhone: '0612345678',
            city: 'Paris',
        );

        // THEN.
        $this->assertSame('10 avenue des Champs', $og->getAddress());
        $this->assertSame('Tour A', $og->getBuilding());
        $this->assertSame(Civility::MR, $og->getCivility());
        $this->assertSame('4791B', $og->getNafCode());
        $this->assertSame('FR', $og->getCountryCode());
        $this->assertSame('75008', $og->getPostalCode());
        $this->assertSame($email, $og->getEmail());
        $this->assertSame('0143000000', $og->getFax());
        $this->assertSame('Les Lilas', $og->getHamlet());
        $this->assertSame($lastName, $og->getLastName());
        $this->assertSame($firstName, $og->getFirstName());
        $this->assertSame('Dupont SARL', $og->getCompanyName());
        $this->assertSame('Logistique', $og->getService());
        $this->assertSame('0143000001', $og->getPhone());
        $this->assertSame('0612345678', $og->getOtherPhone());
        $this->assertSame('Paris', $og->getCity());
    }

    public function testCanInstantiateWithDefaults(): void
    {
        // WHEN.
        $og = new OrderGiver();

        // THEN.
        $this->assertNull($og->getAddress());
        $this->assertNull($og->getCivility());
        $this->assertNull($og->getLastName());
        $this->assertNull($og->getEmail());
        $this->assertNull($og->getCity());
    }
}
