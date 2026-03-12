<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\Shipping;

use Kwaadpepper\ChronopostApiPhp\Dto\Dto;

readonly class PickupConstraint implements Dto
{
    public function __construct(
        public ?string $codeAgence,
        public ?string $nomAgence,
        public ?string $codePays,
        public ?string $codePostal,
        public ?string $ville,
        public ?int $battement,
        public ?string $battementEnHeure,
        public ?string $hla,
        public ?string $hlp,
        public ?string $hppt,
        public ?string $raisonNonActivite,
        public ?bool $zoneA,
    ) {
    }
}
