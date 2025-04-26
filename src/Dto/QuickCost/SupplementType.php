<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\QuickCost;

use Kwaadpepper\ChronopostApiPhp\Dto\Dto;

/**
 * Supplement service type.
 *
 * Known types:
 * - ESD en France métropolitaine
 * - Sup.Retrait Bureau
 * - Participation Eco-Responsable
 * - Sup.Classic livr.du Samedi
 * - Supplement Corse
 * - Livr. Domicile privé
 * - Douane
 * - Redevance sûreté
 * - Retour Express de Paiement
 */
readonly class SupplementType implements Dto
{
    /**
     * @param string $serviceCode Code du service.
     * @param string $label       Libellé du service.
     */
    public function __construct(
        public string $serviceCode,
        public string $label,
    ) {
    }
}
