<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues;

readonly class SenderReference implements \Stringable
{
    private string $value;

    /**
     * @param string $value Sender reference (max 35 alphanumeric characters).
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
        if ($value === '' || mb_strlen($value) > 35) {
            throw new \InvalidArgumentException(
                sprintf('Sender reference must be between 1 and 35 characters, got %d.', mb_strlen($value)),
            );
        }
        if (!preg_match('/^[a-zA-Z0-9\-_ ]+$/', $value)) {
            throw new \InvalidArgumentException('Sender reference contains invalid characters. Only alphanumeric, hyphens, underscores and spaces are allowed.');
        }
    }
}
