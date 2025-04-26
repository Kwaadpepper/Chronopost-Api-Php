<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues;

class ProductCode implements \Stringable
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
}
