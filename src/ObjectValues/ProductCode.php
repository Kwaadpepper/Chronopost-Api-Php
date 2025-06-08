<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues;

use Kwaadpepper\ChronopostApiPhp\Enums\ChronopostProductCode;

readonly class ProductCode implements \Stringable
{
    /**
     * @param string $value Delivery product code.
     *                      The product code must be 1 or 2 characters long,
     *                      consisting of letters and/or digits.
     *                      Doc says it is given by an IT contact from Chronopost.
     *
     * @throws \InvalidArgumentException If the product code is not valid.
     */
    public function __construct(private string $value)
    {
        preg_match(
            '/^[a-zA-Z0-9]{1,2}$/',
            $this->value,
            $matches
        );
        if (count($matches) !== 1) {
            throw new \InvalidArgumentException('Invalid product code');
        }
    }


    /**
     * Get the product code value.
     *
     * @return string The product code value.
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * Get the name of the product code.
     *
     * @return string The name of the product code.
     */
    public function getName(): string
    {
        try {
            return ChronopostProductCode::from($this->value)->name;
        } catch (\ValueError $e) {
            return 'Unknown Product Code';
        }
    }

    /**
     * Create a ProductCode from a ChronopostProductCode enum.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\Enums\ChronopostProductCode $productCode The product code enum.
     *
     * @return self
     */
    public static function fromEnum(ChronopostProductCode $productCode): self
    {
        return new self($productCode->value);
    }
}
