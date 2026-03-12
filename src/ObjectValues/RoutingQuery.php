<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues;

/**
 * Composite query for routing information (getRouting).
 */
readonly class RoutingQuery
{
    public function __construct(
        private string $shipperDepot,
        private PostCode $destination,
        private ?string $socode = null,
        private ?string $ascode = null,
    ) {
        if ($shipperDepot === '') {
            throw new \InvalidArgumentException('Shipper depot must not be empty.');
        }
    }

    public function getShipperDepot(): string
    {
        return $this->shipperDepot;
    }

    public function getDestination(): PostCode
    {
        return $this->destination;
    }

    public function getSocode(): ?string
    {
        return $this->socode;
    }

    public function getAscode(): ?string
    {
        return $this->ascode;
    }
}
