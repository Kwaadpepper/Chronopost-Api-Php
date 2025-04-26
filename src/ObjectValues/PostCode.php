<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues;

use Kwaadpepper\ChronopostApiPhp\Enums\CountryDelivery;

class PostCode implements \Stringable
{
    /**
     * A valid post code for the country.
     * The post code must match the format defined in the country delivery object.
     *
     * @var string
     */
    private string $postCode;

    /**
     * The country delivery object.
     * This object contains the post code format for the country.
     *
     * @var \Kwaadpepper\ChronopostApiPhp\Enums\CountryDelivery
     */
    private CountryDelivery $countryDelivery;

    /**
     * Constructor.
     *
     * @param string                                              $postCode        The post code to validate.
     * @param \Kwaadpepper\ChronopostApiPhp\Enums\CountryDelivery $countryDelivery The country delivery object.
     *
     * @throws \InvalidArgumentException If the post code does not match
     *                                   the format defined in the country delivery object.
     */
    private function __construct(
        string $postCode,
        CountryDelivery $countryDelivery
    ) {
        $this->validate($postCode, $countryDelivery);
        $this->postCode        = $postCode;
        $this->countryDelivery = $countryDelivery;
    }

    /**
     * @return string
     */
    public function getPostCode(): string
    {
        return $this->postCode;
    }

    /**
     * @return \Kwaadpepper\ChronopostApiPhp\Enums\CountryDelivery
     */
    public function getCountryDelivery(): CountryDelivery
    {
        return $this->countryDelivery;
    }

    /**
     * Create a new instance of the PostCode class.
     *
     * @param string                                              $postCode  The post code to validate.
     * @param \Kwaadpepper\ChronopostApiPhp\Enums\CountryDelivery $ofCountry The country delivery object.
     *
     * @return \Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode
     *
     * @throws \InvalidArgumentException If the post code does not match
     *                                   the format defined in the country delivery object.
     */
    public static function create(string $postCode, CountryDelivery $ofCountry): self
    {
        $instance = new self(
            postCode: $postCode,
            countryDelivery: $ofCountry,
        );
        return $instance;
    }

    /**
     * Validate the post code format based on the country delivery object.
     *
     * @param string                                              $postCode  The post code to validate.
     * @param \Kwaadpepper\ChronopostApiPhp\Enums\CountryDelivery $ofCountry The country delivery object.
     *
     * @return void
     *
     * @throws \InvalidArgumentException If the post code does not match
     *                                   the format defined in the country delivery object.
     */
    private function validate(string $postCode, CountryDelivery $ofCountry)
    {
        $postCodes = $ofCountry->getPostCodeFormats();
        foreach ($postCodes as $postCodeFormat) {
            if (preg_match($postCodeFormat, $postCode)) {
                return;
            }
        }
        throw new \InvalidArgumentException(
            sprintf(
                'The post code "%s" is not valid for the country "%s".',
                $postCode,
                $ofCountry->name,
            )
        );
    }

    /**
     * Convert the post code to a string.
     *
     * @return string The post code as a string.
     */
    public function __toString(): string
    {
        return $this->postCode;
    }
}
