<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues;

use Stringable;

class TrackingNumber implements Stringable
{
    /**
     * @param string $value The tracking number.
     *                      The tracking number must be 13 characters long,
     *                          starting with 2 letters, followed by 9 digits and
     *                          ending with 2 letters.
     *                      Example: AB123456789CD
     *                      The tracking number is case insensitive.
     *
     * @throws \InvalidArgumentException If the tracking number is not valid.
     */
    public function __construct(private string $value)
    {
        preg_match(
            '/^[A-Z]{2}[0-9]{9}[A-Z]{2}$/',
            $this->value,
            $matches
        );
        if (count($matches) !== 1) {
            throw new \InvalidArgumentException('Invalid tracking number');
        }
    }

    /**
     * @return string The tracking number.
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return string The tracking number.
     */
    public function __toString(): string
    {
        return $this->value;
    }
}
