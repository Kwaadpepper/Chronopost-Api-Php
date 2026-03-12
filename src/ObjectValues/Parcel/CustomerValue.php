<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel;

use Kwaadpepper\ChronopostApiPhp\Enums\Civility;
use Kwaadpepper\ChronopostApiPhp\Helpers\StringHelper;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PhoneNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode;

/**
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */
readonly class CustomerValue extends ParcelInfo
{
    /**
     * CustomerValue constructor.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\Enums\Civility                $civility
     * @param string                                                      $name
     * @param string                                                      $email
     * @param string                                                      $address1
     * @param string|null                                                 $address2
     * @param string                                                      $city
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode         $postCode
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\PhoneNumber|null $mobilePhone
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\PhoneNumber|null $phone
     * @param string|null                                                 $contactName   Mandatory for an ESD operation.
     * @param boolean                                                     $printAsSender Indicate if the customer address should be printed as the sender.
     * @return void
     *
     * @throws \InvalidArgumentException If the provided argument is invalid.
     */
    public function __construct(
        public Civility $civility,
        string $name,
        string $email,
        string $address1,
        ?string $address2,
        string $city,
        PostCode $postCode,
        ?PhoneNumber $mobilePhone,
        ?PhoneNumber $phone = null,
        string|null $contactName = null,
        public bool $printAsSender = false,
    ) {
        $splittedName  = StringHelper::cutStringToFitOnMultipleLines($name, 100, 2);
        $customerName  = $splittedName[0];
        $customerName2 = !empty($splittedName[1]) ? $splittedName[1] : null;

        parent::__construct(
            $address1,
            $address2,
            $city,
            $contactName,
            $postCode->getCountryDelivery(),
            $email,
            $customerName,
            $customerName2,
            $mobilePhone,
            $phone,
            $postCode,
        );
    }
}
