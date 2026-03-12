<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\Shipping;

use Kwaadpepper\ChronopostApiPhp\Dto\Dto;

readonly class RoutingInfo implements Dto
{
    /**
     * @param string|null                $posteComptable
     * @param array<string, string|null> $geopostData
     */
    public function __construct(
        public ?string $posteComptable,
        public array $geopostData,
    ) {
    }
}
