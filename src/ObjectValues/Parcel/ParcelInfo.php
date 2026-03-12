<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel;

use Kwaadpepper\ChronopostApiPhp\Enums\CountryForChronopost;
use Kwaadpepper\ChronopostApiPhp\Helpers\StringHelper;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PhoneNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode;

abstract readonly class ParcelInfo
{
    /**
     * CcountryName
     *
     * @var string
     */
    public string $countryName;

    /**
     * ShippingValue
     *
     * @param string                                                      $address1
     * @param string|null                                                 $address2
     * @param string                                                      $city
     * @param string|null                                                 $contactName Mandatory for an ESD operation.
     * @param \Kwaadpepper\ChronopostApiPhp\Enums\CountryForChronopost    $country
     * @param string                                                      $email
     * @param string                                                      $name
     * @param string|null                                                 $name2
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\PhoneNumber|null $mobilePhone
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\PhoneNumber|null $phone
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode         $postCode
     *
     * @throws \InvalidArgumentException If the provided argument is invalid.
     */
    public function __construct(
        public string $address1,
        public ?string $address2,
        public string $city,
        public ?string $contactName,
        public CountryForChronopost $country,
        public string $email,
        public string $name,
        public string|null $name2,
        public ?PhoneNumber $mobilePhone,
        public ?PhoneNumber $phone,
        public PostCode $postCode,
    ) {
        if ($mobilePhone !== null && !$mobilePhone->isMobile()) {
            throw new \InvalidArgumentException(
                'Mobile phone number must be a mobile number for ParcelInfo.',
            );
        }
        if (is_null($phone) && $mobilePhone !== null) {
            $phone = clone $mobilePhone;
        }
        $this->countryName = $country->getDisplayableName();
        StringHelper::validateValue($address1, 'address1', '/^[a-zA-Z0-9]{0,38}$/');
        StringHelper::validateValue($address2, 'address2', '/^[a-zA-Z0-9]{0,38}$/');
        StringHelper::validateValue($city, 'city', '/^[a-zA-Z0-9]{0,50}$/');
        StringHelper::validateValue($contactName, 'city', '/^[a-zA-Z0-9]{0,100}$/');
        StringHelper::validateValue($email, 'email', '/^[!#$%\'\*\+\-\/\=\?\^\_\`\.\{\}a-zA-Z0-9]{0,80}$/');
        StringHelper::validateValue($name, 'name', '/^[a-zA-Z0-9]{0,100}$/');
        StringHelper::validateValue($name2, 'name2', '/^[a-zA-Z0-9]{0,100}$/');
    }
}
