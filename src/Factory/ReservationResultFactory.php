<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Factory;

use ChronopostShipping\StructType\ResultReservationExpeditionValue;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\EsdInfo;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\ReservationResult;

class ReservationResultFactory implements Factory
{
    /**
     * Create a new instance of ReservationResult.
     *
     * @param \ChronopostShipping\StructType\ResultReservationExpeditionValue $result
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\ReservationResult
     * @phpcs:disable Squiz.Commenting.FunctionComment.TypeHintMissing
     */
    public function create($result): ReservationResult
    {
        return new ReservationResult(
            reservationNumber: $result->getReservationNumber(),
            skybillNumber: $result->getSkybillNumber(),
            codeDepot: $result->getCodeDepot(),
            codeService: $result->getCodeService(),
            destinationDepot: $result->getDestinationDepot(),
            geoPostCodeBarre: $result->getGeoPostCodeBarre(),
            geoPostNumeroColis: $result->getGeoPostNumeroColis(),
            groupingPriorityLabel: $result->getGroupingPriorityLabel(),
            serviceMark: $result->getServiceMark(),
            serviceName: $result->getServiceName(),
            signaletiqueProduit: $result->getSignaletiqueProduit(),
            dSort: $result->getDSort(),
            oSort: $result->getOSort(),
            esdInfo: $this->mapToEsdInfo($result),
        );
    }

    /**
     * Get Esd info from result.
     *
     * @param \ChronopostShipping\StructType\ResultReservationExpeditionValue $result
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\EsdInfo|null
     */
    private function mapToEsdInfo(ResultReservationExpeditionValue $result): EsdInfo|null
    {
        $esdFullNumber = $result->getESDFullNumber();
        $esdNumber     = $result->getESDNumber();
        $pickupDate    = $result->getPickupDate();

        if ($esdFullNumber !== null && $esdNumber !== null && $pickupDate !== null) {
            $parsedDate = \DateTimeImmutable::createFromFormat(
                'Y-m-d\TH:i:s',
                $pickupDate,
            );

            if ($parsedDate === false) {
                try {
                    $parsedDate = new \DateTimeImmutable($pickupDate);
                } catch (\Exception) {
                    return null;
                }
            }

            return new EsdInfo(
                $esdFullNumber,
                $esdNumber,
                $parsedDate,
            );
        }

        return null;
    }
}
