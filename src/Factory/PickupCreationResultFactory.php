<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Factory;

use ChronopostShipping\StructType\InfoEnlevement;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\PickupCreationResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\PickupInfo;

class PickupCreationResultFactory implements Factory
{
    /**
     * @param \ChronopostShipping\StructType\ResultEnlevementNational|\ChronopostShipping\StructType\ResultPickupOrCollectionRequest $response
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\PickupCreationResult
     */
    public function create($response): PickupCreationResult
    {
        $infos = $this->extractInfoEnlevements($response);
        $primaryEsd = '';

        if ($infos !== []) {
            $primaryEsd = $infos[0]->getNumeroUniqueESD() ?? '';
        }

        return new PickupCreationResult(
            numeroUniqueESD: $primaryEsd,
            pickupInfos: array_map($this->mapPickupInfo(...), $infos),
        );
    }

    /**
     * @return InfoEnlevement[]
     */
    private function extractInfoEnlevements(object $response): array
    {
        if (method_exists($response, 'getInfoEnlevements')) {
            return $response->getInfoEnlevements() ?? [];
        }

        if (method_exists($response, 'getInfoEnlevement')) {
            $info = $response->getInfoEnlevement();
            return $info !== null ? [$info] : [];
        }

        return [];
    }

    private function mapPickupInfo(InfoEnlevement $info): PickupInfo
    {
        return new PickupInfo(
            idEnlevement: $info->getIdEnlevement(),
            numeroUniqueESD: $info->getNumeroUniqueESD(),
            ancienNumeroESD: $info->getAncienNumeroESD(),
            codeBu: $info->getCodeBu(),
            codeDepot: $info->getCodeDepot(),
            codePostal: $info->getCodePostal(),
            ville: $info->getVille(),
            dateCreation: $info->getDateCreation(),
            datePassage: $info->getDatePassage(),
            refDestinataire: $info->getRefDestinataire(),
            refEsdClient: $info->getRefEsdClient(),
        );
    }
}
