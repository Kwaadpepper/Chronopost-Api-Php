<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues;

readonly class ParcelDimensions
{
    private float $height;
    private float $length;
    private float $width;

    /**
     * @param float $height Height in centimeters (> 0).
     * @param float $length Length in centimeters (> 0).
     * @param float $width  Width in centimeters (> 0).
     */
    public function __construct(float $height, float $length, float $width)
    {
        $this->validate($height, $length, $width);
        $this->height = $height;
        $this->length = $length;
        $this->width  = $width;
    }

    public function getHeight(): float
    {
        return $this->height;
    }

    public function getLength(): float
    {
        return $this->length;
    }

    public function getWidth(): float
    {
        return $this->width;
    }

    private function validate(float $height, float $length, float $width): void
    {
        if ($height <= 0) {
            throw new \InvalidArgumentException(
                sprintf('Height must be greater than 0, got %s.', $height),
            );
        }
        if ($length <= 0) {
            throw new \InvalidArgumentException(
                sprintf('Length must be greater than 0, got %s.', $length),
            );
        }
        if ($width <= 0) {
            throw new \InvalidArgumentException(
                sprintf('Width must be greater than 0, got %s.', $width),
            );
        }
    }
}
