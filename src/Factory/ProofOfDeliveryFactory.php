<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Factory;

use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\ProofOfDelivery;

class ProofOfDeliveryFactory implements Factory
{
    /**
     * Create a ProofOfDelivery from a ResultSearchPOD.
     *
     * @param \ChronopostTracking\StructType\ResultSearchPOD $result
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\ProofOfDelivery
     * @phpcs:disable Squiz.Commenting.FunctionComment.TypeHintMissing
     */
    public function create($result)
    {
        // phpcs:enable
        return new ProofOfDelivery(
            podPresent: (bool)$result->getPodPresente(),
            format: $result->getFormatPOD(),
            podData: $result->getPod(),
            statusCode: (int)$result->getStatusCode(),
        );
    }
}
