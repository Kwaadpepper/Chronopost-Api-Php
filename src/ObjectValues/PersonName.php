<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues;

readonly class PersonName implements \Stringable
{
    private string $value;

    /**
     * @param string $value Person name (max 100 characters).
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
        if ($value === '' || mb_strlen($value) > 100) {
            throw new \InvalidArgumentException(
                sprintf('Person name must be between 1 and 100 characters, got %d.', mb_strlen($value)),
            );
        }
        if (!preg_match('/^[a-zA-ZÀ-ÿ\s\-\'.]+$/', $value)) {
            throw new \InvalidArgumentException('Person name contains invalid characters.');
        }
    }
}
