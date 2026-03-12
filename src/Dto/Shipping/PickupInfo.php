<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\Shipping;

use Kwaadpepper\ChronopostApiPhp\Dto\Dto;

readonly class PickupInfo implements Dto
{
    public function __construct(
        public ?int $idEnlevement,
        public ?string $numeroUniqueESD,
        public ?string $ancienNumeroESD,
        public ?string $codeBu,
        public ?string $codeDepot,
        public ?string $codePostal,
        public ?string $ville,
        public ?string $dateCreation,
        public ?string $datePassage,
        public ?string $refDestinataire,
        public ?string $refEsdClient,
    ) {
    }
}
