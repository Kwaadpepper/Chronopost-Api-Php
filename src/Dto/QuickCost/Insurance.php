<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\QuickCost;

use Kwaadpepper\ChronopostApiPhp\Dto\Dto;
use Money\Money;

/**
 * Montant de l’assurance lié à l’envoi.
 */
readonly class Insurance implements Dto
{
    /**
     * @param \Money\Money $ceil Montant maximum de l’assurance.
     * @param float        $rate Taux de l’assurance.
     */
    public function __construct(
        public Money $ceil,
        public float $rate,
    ) {
    }
}
