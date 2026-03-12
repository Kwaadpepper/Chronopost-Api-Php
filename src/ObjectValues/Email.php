<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues;

readonly class Email implements \Stringable
{
    private string $value;

    /**
     * @param string $value Email address (max 80 characters, valid format).
     */
    public function __construct(string $value)
    {
        $this->validate($value);
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    private function validate(string $value): void
    {
        if (mb_strlen($value) > 80) {
            throw new \InvalidArgumentException(
                sprintf('Email must not exceed 80 characters, got %d.', mb_strlen($value)),
            );
        }
        if (filter_var($value, \FILTER_VALIDATE_EMAIL) === false) {
            throw new \InvalidArgumentException(
                sprintf('Invalid email format: "%s".', $value),
            );
        }
    }
}
