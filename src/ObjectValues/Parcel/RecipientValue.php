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
readonly class RecipientValue extends ParcelInfo
{
    /**
     * 0 means no pre-alert, 22 means pre-alert.
     *
     * @var integer Indicates if the recipient should be pre-alerted.
     */
    public int $recipientPreAlert;

    /**
     * ShipperValue
     *
     * @param \Kwaadpepper\ChronopostApiPhp\Enums\Civility                $civility
     * @param string                                                      $name
     * @param string                                                      $email
     * @param string                                                      $address1
     * @param string|null                                                 $address2
     * @param string                                                      $city
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode         $postCode
     * @param \Kwaadpepper\ChronopostApiPhp\Enums\ParcelInfoType          $recipientType The type of recipient (e.g., individual, business).
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\PhoneNumber      $mobilePhone
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\PhoneNumber|null $phone         Has to be a mobile phone for a relay point, but can be a landline for a home delivery.
     * @param boolean                                                     $preAlert      Indicates if the recipient should be pre-alerted.
     * @param string|null                                                 $contactName   Mandatory for an ESD operation.
     */
    public function __construct(
        public Civility $civility,
        string $name,
        string $email,
        string $address1,
        string|null $address2,
        string $city,
        PostCode $postCode,
        public ParcelInfoType $recipientType,
        PhoneNumber $mobilePhone,
        PhoneNumber|null $phone = null,
        bool $preAlert = false,
        string|null $contactName = null,
    ) {
        $splittedName = StringHelper::cutStringToFitOnMultipleLines($name, 100);
        $shipperName  = $splittedName[0];
        $shipperName2 = !empty($splittedName[1]) ? $splittedName[1] : null;

        $this->recipientPreAlert = $preAlert ? 22 : 0;

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
