<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Factory;

use ChronopostShipping\StructType\EsdContraintesAgence;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\PickupConstraint;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\PickupConstraints;

class PickupConstraintsFactory implements Factory
{
    /**
     * @param \ChronopostShipping\StructType\EsdResultContraintesAgenceValue $response
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\PickupConstraints
     */
    public function create($response): PickupConstraints
    {
        $agences = $response->getEsdContraintesAgence() ?? [];

        return new PickupConstraints(
            errorCode: $response->getCodeErreur() ?? 0,
            errorMessage: $response->getLibelleErreur(),
            constraints: array_map($this->mapConstraint(...), $agences),
        );
    }

    private function mapConstraint(EsdContraintesAgence $agence): PickupConstraint
    {
        return new PickupConstraint(
            codeAgence: $agence->getCodeAgence(),
            nomAgence: $agence->getNomAgence(),
            codePays: $agence->getCodePays(),
            codePostal: $agence->getCodePostal(),
            ville: $agence->getVille(),
            battement: $agence->getBattement(),
            battementEnHeure: $agence->getBattementEnHeure(),
            hla: $agence->getHla(),
            hlp: $agence->getHlp(),
            hppt: $agence->getHppt(),
            raisonNonActivite: $agence->getRaisonNonActivite(),
            zoneA: $agence->getZoneA(),
        );
    }
}
