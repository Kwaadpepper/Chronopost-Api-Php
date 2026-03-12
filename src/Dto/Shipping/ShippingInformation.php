<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\Shipping;

use Kwaadpepper\ChronopostApiPhp\Dto\Dto;

readonly class ShippingInformation implements Dto
{
    public function __construct(
        public ?string $asCode,
        public ?string $codeService,
        public ?string $destinationDepot,
        public ?string $groupingPriorityLabel,
        public ?string $serviceMark,
        public ?string $serviceName,
        public ?string $signaletiqueProduit,
        public ?string $dSort,
        public ?string $oSort,
    ) {
    }
}
