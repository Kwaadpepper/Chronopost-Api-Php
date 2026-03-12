<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Factory;

use ChronopostTracking\StructType\ParcelPOD;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\ParcelProofOfDelivery;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\ProofOfDeliveryByRef;

class ProofOfDeliveryByRefFactory implements Factory
{
    /**
     * Create a ProofOfDeliveryByRef from a ResultSearchPODWithSenderRef.
     *
     * @param \ChronopostTracking\StructType\ResultSearchPODWithSenderRef $result
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\ProofOfDeliveryByRef
     * @phpcs:disable Squiz.Commenting.FunctionComment.TypeHintMissing
     */
    public function create($result)
    {
        // phpcs:enable
        $parcelPods = $result->getListParcelPOD() ?? [];

        $parcels = array_map(
            fn (ParcelPOD $parcelPod) => $this->mapParcelPod($parcelPod),
            $parcelPods,
        );

        return new ProofOfDeliveryByRef(parcels: $parcels);
    }

    /**
     * @param \ChronopostTracking\StructType\ParcelPOD $parcelPod
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\ParcelProofOfDelivery
     */
    private function mapParcelPod(ParcelPOD $parcelPod): ParcelProofOfDelivery
    {
        return new ParcelProofOfDelivery(
            skybillNumber: (string) $parcelPod->getSkybillNumber(),
            podPresent: (bool) $parcelPod->getPodPresente(),
            format: $parcelPod->getFormatPOD(),
            podData: $parcelPod->getPod(),
            statusCode: (int) $parcelPod->getStatusCode(),
        );
    }
}
