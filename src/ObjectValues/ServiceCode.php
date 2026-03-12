<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues;

use Kwaadpepper\ChronopostApiPhp\Enums\DeliveryServiceCode;

/**
 * Represents a service code for Chronopost delivery.
 * The service code is used to specify the delivery day.
 * It must be 1 or 3 characters long, consisting of digits.
 * The service code is provided by an IT contact from Chronopost.
 */
readonly class ServiceCode implements \Stringable
{
    /**
     * @param string $value Service code (delivery day).
     *                      The service code must be 1 or 3 characters long,
     *                      consisting of digits.
     *                      Doc says it is given by an IT contact from Chronopost.
     *
     * @throws \InvalidArgumentException If the service code is not valid.
     */
    public function __construct(private string $value)
    {
        preg_match(
            '/^[0-9]{1}|[0-9]{3}$/',
            $this->value,
            $matches,
        );
        if (count($matches) !== 1) {
            throw new \InvalidArgumentException('Invalid service code');
        }
    }

    /**
     * Get the service code value.
     *
     * @return string The service code value.
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Get the name of the service code.
     *
     * @return string The name of the service code.
     */
    public function getName(): string
    {
        try {
            return DeliveryServiceCode::from($this->value)->name;
        } catch (\ValueError $e) {
            return 'Unknown Service Code';
        }
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * Create a ServiceCode instance from a DeliveryServiceCode enum.
     *
     * @phpcs:disable Generic.Files.LineLength.TooLong
     *
     * @param \Kwaadpepper\ChronopostApiPhp\Enums\DeliveryServiceCode $deliveryServiceCode The delivery service code enum.
     *
     * @return self A new ServiceCode instance.
     */
    public static function fromEnum(DeliveryServiceCode $deliveryServiceCode): self
    {
        return new self($deliveryServiceCode->value);
    }
}
