<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Factory;

use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\PickupFeasibility;

class PickupFeasibilityFactory implements Factory
{
    /**
     * @param \ChronopostShipping\StructType\ResultFaisabiliteESD $response
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\PickupFeasibility
     */
    public function create($response): PickupFeasibility
    {
        $errorCode = $response->getErrorCode() ?? 0;

        return new PickupFeasibility(
            errorCode: $errorCode,
            errorMessage: $response->getErrorMessage(),
            feasible: $errorCode === 0,
        );
    }
}
