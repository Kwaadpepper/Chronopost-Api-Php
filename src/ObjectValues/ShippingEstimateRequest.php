<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues;

use Kwaadpepper\ChronopostApiPhp\Enums\ShippingType;

/**
 * Composite request for shipping estimation (calculateProducts, calculateProductsV2, getProducts).
 */
readonly class ShippingEstimateRequest
{
    public function __construct(
        private PostCode $from,
        private PostCode $to,
        private string $toCityName,
        private ShippingType $shippingType,
        private Weight $weight,
        private ?ParcelDimensions $dimensions = null,
        private ?\DateTimeInterface $shippingDate = null,
    ) {
        if ($toCityName === '') {
            throw new \InvalidArgumentException('Destination city name must not be empty.');
        }
    }

    public function getFrom(): PostCode
    {
        return $this->from;
    }

    public function getTo(): PostCode
    {
        return $this->to;
    }

    public function getToCityName(): string
    {
        return $this->toCityName;
    }

    public function getShippingType(): ShippingType
    {
        return $this->shippingType;
    }

    public function getWeight(): Weight
    {
        return $this->weight;
    }

    public function getDimensions(): ?ParcelDimensions
    {
        return $this->dimensions;
    }

    public function getShippingDate(): ?\DateTimeInterface
    {
        return $this->shippingDate;
    }
}
