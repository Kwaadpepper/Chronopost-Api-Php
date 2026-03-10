<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel;

use Kwaadpepper\ChronopostApiPhp\Enums\Civility;
use Kwaadpepper\ChronopostApiPhp\Enums\ParcelInfoType;
use Kwaadpepper\ChronopostApiPhp\Helpers\StringHelper;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PhoneNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode;

/**
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */
readonly class ShipperValue extends ParcelInfo
{
    /**
     * 0 means no pre-alert, 11 means pre-alert.
     *
     * @var integer Indicates if the recipient should be pre-alerted.
     */
    public int $shipperPreAlert;

    /**
     * ShipperValue constructor.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\Enums\Civility                $civility
     * @param string                                                      $name
     * @param string                                                      $email
     * @param string                                                      $address1
     * @param string|null                                                 $address2
     * @param string                                                      $city
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode         $postCode
     * @param \Kwaadpepper\ChronopostApiPhp\Enums\ParcelInfoType          $shipperType
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\PhoneNumber|null $mobilePhone
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\PhoneNumber|null $phone
     * @param boolean                                                     $preAlert    Indicates if the recipient should be pre-alerted.
     * @param string|null                                                 $contactName Mandatory for an ESD operation.
     *
     * @throws \InvalidArgumentException If the provided argument is invalid.
     * @phpcs:disable Squiz.Commenting.FunctionCommentThrowTag.WrongNumber
     */
    public function __construct(
        public Civility $civility,
        string $name,
        string $email,
        string $address1,
        string|null $address2,
        string $city,
        PostCode $postCode,
        public ParcelInfoType $shipperType,
        ?PhoneNumber $mobilePhone,
        ?PhoneNumber $phone = null,
        bool $preAlert = false,
        string|null $contactName = null,
    ) {

        StringHelper::validateValue($name, 'name', '/^[a-zA-Z0-9]{0,100}$/');
        $shipperName  = $name;
        $shipperName2 = null;

        if ($shipperType == ParcelInfoType::COMPANY) {
            $shipperName2 = $name;
        }

        $this->shipperPreAlert = $preAlert ? 11 : 0;

        parent::__construct(
            $address1,
            $address2,
            $city,
            $contactName,
            $postCode->getCountryDelivery(),
            $email,
            $shipperName,
            $shipperName2,
            $mobilePhone,
            $phone,
            $postCode
        );
    }
}
