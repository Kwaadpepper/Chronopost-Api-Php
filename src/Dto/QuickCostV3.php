<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto;

use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\Insurance;
use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\Overload;
use Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\Supplement;
use Money\Money;

/**
 * Prix d’une expédition en fonction de différents critères
 *
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */
readonly class QuickCostV3 implements Dto
{
    /**
     *
     * @param \Money\Money                                             $amount      Montant hors taxe du colis en fonction du poids saisi.
     * @param \Money\Money                                             $amountTtc   Montant TTC du colis en fonction du poids saisi.
     * @param \Money\Money                                             $amoutTva    Montant de la TVA du colis en fonction du poids saisi.
     * @param string                                                   $zone        Zone tarifaire liée au pays de livraison.
     * @param \Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\Supplement[] $supplements Liste des services disponibles.
     * @param \Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\Insurance    $insurance   Montant de l’assurance lié à l’envoi.
     * @param \Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\Overload     $capacity    Montant de la surcharge carburant lié à l’envoi.
     * @throws \InvalidArgumentException Exception if $services is not an array of Supplement.
     * @phpstan-ignore throws.unusedType
     */
    public function __construct(
        public Money $amount,
        public Money $amountTtc,
        public Money $amoutTva,
        public string $zone,
        protected array $supplements,
        public Insurance $insurance,
        public Overload $capacity,
    ) {
        foreach ($supplements as $supplement) {
            // @phpstan-ignore instanceof.alwaysTrue
            if (!$supplement instanceof Supplement) {
                throw new \InvalidArgumentException('Services must be an array of ' . Supplement::class);
            }
        }
    }

    /**
     * Get the services
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\Supplement[]
     */
    public function getServices(): array
    {
        return $this->supplements;
    }
}
