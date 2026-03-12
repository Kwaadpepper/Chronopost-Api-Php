<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup;

/**
 * Shipment particularities for a DPD recipient.
 */
readonly class DpdParticularities
{
    public function __construct(
        private ?float $height = null,
        private ?string $specialInstructions = null,
        private ?float $width = null,
        private ?float $length = null,
        private ?int $shipmentCount = null,
        private ?float $weight = null,
    ) {
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function getSpecialInstructions(): ?string
    {
        return $this->specialInstructions;
    }

    public function getWidth(): ?float
    {
        return $this->width;
    }

    public function getLength(): ?float
    {
        return $this->length;
    }

    public function getShipmentCount(): ?int
    {
        return $this->shipmentCount;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }
}
