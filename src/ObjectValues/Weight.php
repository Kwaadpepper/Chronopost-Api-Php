<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues;

readonly class Weight implements \Stringable
{
    private float $kg;

    /**
     * @param float $kg Weight in kilograms (0.01–99 kg).
     */
    public function __construct(float $kg)
    {
        $this->validate($kg);
        $this->kg = $kg;
    }

    public function getKg(): float
    {
        return $this->kg;
    }

    public function __toString(): string
    {
        return (string) $this->kg;
    }

    private function validate(float $kg): void
    {
        if ($kg < 0.01 || $kg > 99) {
            throw new \InvalidArgumentException(
                sprintf('Weight must be between 0.01 and 99 kg, got %s.', $kg),
            );
        }
    }
}
