<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues;

use Kwaadpepper\ChronopostApiPhp\Enums\CountryForChronopost;
use Kwaadpepper\ChronopostApiPhp\Enums\PhoneNumberType;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumber as PhoneNumberLib;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberType as PhoneNumberTypeLib;
use libphonenumber\PhoneNumberUtil;

readonly class PhoneNumber
{
    /**
     * The phone number
     *
     * @var \libphonenumber\PhoneNumber
     */
    protected PhoneNumberLib $phoneNumber;

    /**
     * The type of the phone number
     *
     * @var \Kwaadpepper\ChronopostApiPhp\Enums\PhoneNumberType
     */
    protected PhoneNumberType $type;

    /**
     * PhoneNumber constructor.
     *
     * @param string               $phoneNumber          The phone number to validate.
     * @param CountryForChronopost $countryForChronopost The country for the phone number.
     * @return void
     *
     * @throws \InvalidArgumentException If the phone number is invalid.
     */
    public function __construct(
        #[\SensitiveParameter] string $phoneNumber,
        protected CountryForChronopost $countryForChronopost,
    ) {
        $phoneUtil         = PhoneNumberUtil::getInstance();
        $countryCode       = $countryForChronopost->getCode();
        $parsedPhoneNumber = $this->parseToPhoneNumber($phoneNumber, $countryCode);

        if ($parsedPhoneNumber === null) {
            throw new \InvalidArgumentException(sprintf('Invalid phone number format: %s', $phoneNumber));
        }



        $this->phoneNumber = $parsedPhoneNumber;

        $this->type = match ($phoneUtil->getNumberType($this->phoneNumber)) {
            PhoneNumberTypeLib::MOBILE => PhoneNumberType::MOBILE,
            PhoneNumberTypeLib::FIXED_LINE => PhoneNumberType::FIXED,
            PhoneNumberTypeLib::FIXED_LINE_OR_MOBILE => PhoneNumberType::MOBILE,
            PhoneNumberTypeLib::UNKNOWN => throw new \InvalidArgumentException(
                sprintf('Unknown phone number type for: %s', $phoneNumber)
            ),
            default => PhoneNumberType::OTHER,
        };
    }

    /**
     * Is a mobile phone number.
     *
     * @return boolean
     */
    public function isMobile(): bool
    {
        return $this->type === PhoneNumberType::MOBILE;
    }

    /**
     * Is a fixed phone number.
     *
     * @return boolean
     */
    public function isFixed(): bool
    {
        return $this->type === PhoneNumberType::FIXED;
    }

    /**
     * Gets international phone number.
     *
     * @return string
     */
    public function getInternationalPhoneNumber(): string
    {
        $phoneUtil = PhoneNumberUtil::getInstance();
        return $phoneUtil->format($this->phoneNumber, PhoneNumberFormat::INTERNATIONAL);
    }

    /**
     * Gets the phone number in E.164 format.
     *
     * @return string
     */
    public function getE164PhoneNumber(): string
    {
        $phoneUtil = PhoneNumberUtil::getInstance();
        return $phoneUtil->format($this->phoneNumber, PhoneNumberFormat::E164);
    }

    /**
     * Gets the phone number in national format.
     *
     * @return string
     */
    public function getNationalPhoneNumber(): string
    {
        $phoneUtil = PhoneNumberUtil::getInstance();
        return $phoneUtil->format($this->phoneNumber, PhoneNumberFormat::NATIONAL);
    }
    /**
     * Gets the phone number in RFC3966 format.
     *
     * @return string
     */
    public function getRfc3966PhoneNumber(): string
    {
        $phoneUtil = PhoneNumberUtil::getInstance();
        return $phoneUtil->format($this->phoneNumber, PhoneNumberFormat::RFC3966);
    }

    /**
     * Gets the phone number type.
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Enums\PhoneNumberType
     */
    public function getType(): PhoneNumberType
    {
        return $this->type;
    }

    /**
     * Validates the phone number format.
     *
     * @param string $phoneNumber The phone number to validate.
     * @param string $countryCode The country code for the phone number.
     * @return \libphonenumber\PhoneNumber|null
     */
    private function parseToPhoneNumber(
        #[\SensitiveParameter] string $phoneNumber,
        string $countryCode
    ): PhoneNumberLib|null {
        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            $phoneNumber = $phoneUtil->parse($phoneNumber, $countryCode);
            if (!$phoneUtil->isValidNumber($phoneNumber)) {
                return null;
            }
            return $phoneNumber;
        } catch (NumberParseException $e) {
            return null;
        }
    }
}
