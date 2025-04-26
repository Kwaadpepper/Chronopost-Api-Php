<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\QuickCost;

use Kwaadpepper\ChronopostApiPhp\Dto\Dto;

/**
 * Pourcentage de la surcharge carburant liée au carburant.
 */
class Overload implements Dto
{
    /**
     * @param float $planePercent Pourcentage de la surcharge carburant Avion.
     * @param float $roadPercent  Pourcentage de la surcharge carburant Route.
     */
    public function __construct(
        public float $planePercent,
        public float $roadPercent,
    ) {
    }
}
