<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues\Pickup;

/**
 * ESD particularities for national pickup creation.
 */
readonly class EsdParticularities
{
    public function __construct(
        private ?bool $feasibilityStudy = null,
        private ?bool $bulkyVolume = null,
        private ?int $height = null,
        private ?int $width = null,
        private ?int $length = null,
        private ?string $specialInstructions = null,
        private ?string $announcedParcels = null,
        private ?int $shipmentCount = null,
        private ?float $weight = null,
        private ?string $volume = null,
    ) {
    }

    public function getFeasibilityStudy(): ?bool
    {
        return $this->feasibilityStudy;
    }

    public function getBulkyVolume(): ?bool
    {
        return $this->bulkyVolume;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function getSpecialInstructions(): ?string
    {
        return $this->specialInstructions;
    }

    public function getAnnouncedParcels(): ?string
    {
        return $this->announcedParcels;
    }

    public function getShipmentCount(): ?int
    {
        return $this->shipmentCount;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function getVolume(): ?string
    {
        return $this->volume;
    }
}
