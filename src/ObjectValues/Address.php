<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues;

readonly class Address
{
    private string $address1;
    private ?string $address2;
    private string $city;
    private PostCode $postCode;

    /**
     * @param string      $address1 Primary address line (max 38 alphanumeric characters).
     * @param string|null $address2 Secondary address line (max 38 alphanumeric characters).
     * @param string      $city     City name (max 50 characters).
     * @param PostCode    $postCode Postal code with country.
     */
    public function __construct(
        string $address1,
        ?string $address2,
        string $city,
        PostCode $postCode,
    ) {
        $this->validate($address1, $address2, $city);
        $this->address1 = $address1;
        $this->address2 = $address2;
        $this->city     = $city;
        $this->postCode = $postCode;
    }

    public function getAddress1(): string
    {
        return $this->address1;
    }

    public function getAddress2(): ?string
    {
        return $this->address2;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getPostCode(): PostCode
    {
        return $this->postCode;
    }

    private function validate(string $address1, ?string $address2, string $city): void
    {
        if ($address1 === '' || mb_strlen($address1) > 38) {
            throw new \InvalidArgumentException(
                sprintf('Address line 1 must be between 1 and 38 characters, got %d.', mb_strlen($address1)),
            );
        }
        if (!preg_match('/^[a-zA-Z0-9À-ÿ\s\-\',.\/#]+$/', $address1)) {
            throw new \InvalidArgumentException('Address line 1 contains invalid characters.');
        }

        if ($address2 !== null && $address2 !== '') {
            if (mb_strlen($address2) > 38) {
                throw new \InvalidArgumentException(
                    sprintf('Address line 2 must not exceed 38 characters, got %d.', mb_strlen($address2)),
                );
            }
            if (!preg_match('/^[a-zA-Z0-9À-ÿ\s\-\',.\/#]+$/', $address2)) {
                throw new \InvalidArgumentException('Address line 2 contains invalid characters.');
            }
        }

        if ($city === '' || mb_strlen($city) > 50) {
            throw new \InvalidArgumentException(
                sprintf('City must be between 1 and 50 characters, got %d.', mb_strlen($city)),
            );
        }
    }
}
