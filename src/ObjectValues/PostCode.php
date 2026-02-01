<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues;

use Kwaadpepper\ChronopostApiPhp\Enums\CountryForChronopost;

readonly class PostCode implements \Stringable
{
    /**
     * A valid post code for the country.
     * The post code must match the format defined in the country delivery object.
     */
    private string $postCode;

    /**
     * The country delivery object.
     * This object contains the post code format for the country.
     */
    private CountryForChronopost $countryDelivery;

    /**
     * Constructor.
     *
     * @param  string  $postCode  The post code to validate.
     * @param  \Kwaadpepper\ChronopostApiPhp\Enums\CountryForChronopost  $countryDelivery  The country delivery object.
     *
     * @throws \InvalidArgumentException If the post code does not match
     *                                   the format defined in the country delivery object.
     */
    public function __construct(
        string $postCode,
        CountryForChronopost $countryDelivery
    ) {
        $this->validate($postCode, $countryDelivery);
        $this->postCode        = $postCode;
        $this->countryDelivery = $countryDelivery;
    }

    public function getPostCode(): string
    {
        return $this->postCode;
    }

    public function getCountryDelivery(): CountryForChronopost
    {
        return $this->countryDelivery;
    }

    /**
     * Validate the post code format based on the country delivery object.
     *
     * @param  string  $postCode  The post code to validate.
     * @param  \Kwaadpepper\ChronopostApiPhp\Enums\CountryForChronopost  $ofCountry  The country delivery object.
     * @return void
     *
     * @throws \InvalidArgumentException If the post code does not match
     *                                   the format defined in the country delivery object.
     */
    private function validate(string $postCode, CountryForChronopost $ofCountry)
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
