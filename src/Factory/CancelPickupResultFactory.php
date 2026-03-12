<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Factory;

use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\CancelPickupResult;

class CancelPickupResultFactory implements Factory
{
    /**
     * @param \ChronopostShipping\StructType\ResultAnnulerEnlevement $response
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\CancelPickupResult
     */
    public function create($response): CancelPickupResult
    {
        $statuses = [];
        $statut = $response->getStatut();

        if ($statut !== null) {
            $entries = $statut->getEntry() ?? [];
            foreach ($entries as $entry) {
                $key = $entry->getKey();
                if ($key !== null) {
                    $statuses[$key] = $entry->getValue();
                }
            }
        }

        return new CancelPickupResult(
            errorCode: $response->getCodeErreur() ?? 0,
            errorMessage: $response->getErrorMessage(),
            statuses: $statuses,
        );
    }
}
