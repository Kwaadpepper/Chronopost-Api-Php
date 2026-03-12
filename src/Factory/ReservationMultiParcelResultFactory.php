<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Factory;

use ChronopostShipping\StructType\ResultParcelValue;
use ChronopostShipping\StructType\ResultReservationMultiParcelExpeditionValue;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\EsdInfo;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\ReservationMultiParcelResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\ReservationParcelValue;

class ReservationMultiParcelResultFactory implements Factory
{
    /**
     * Create a new instance of ReservationMultiParcelResult.
     *
     * @param \ChronopostShipping\StructType\ResultReservationMultiParcelExpeditionValue $result
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\ReservationMultiParcelResult
     * @phpcs:disable Squiz.Commenting.FunctionComment.TypeHintMissing
     */
    public function create($result): ReservationMultiParcelResult
    {
        $resultParcels = $result->getResultParcelValue() ?? [];
        $parcelValues  = array_map(
            fn(ResultParcelValue $parcelValue) => $this->mapToReservationParcelValue($parcelValue),
            $resultParcels
        );

        return new ReservationMultiParcelResult(
            parcelValues: $parcelValues,
            reservationNumber: $result->getReservationNumber(),
            esdInfo: $this->mapToEsdInfo($result),
        );
    }

    /**
     * Map a ResultParcelValue to a ReservationParcelValue.
     *
     * @param \ChronopostShipping\StructType\ResultParcelValue $parcelValue
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\ReservationParcelValue
     */
    private function mapToReservationParcelValue(ResultParcelValue $parcelValue): ReservationParcelValue
    {
        return new ReservationParcelValue(
            skybillNumber: $parcelValue->getSkybillNumber(),
            codeDepot: $parcelValue->getCodeDepot(),
            codeService: $parcelValue->getCodeService(),
            destinationDepot: $parcelValue->getDestinationDepot(),
            geoPostCodeBarre: $parcelValue->getGeoPostCodeBarre(),
            geoPostNumeroColis: $parcelValue->getGeoPostNumeroColis(),
            groupingPriorityLabel: $parcelValue->getGroupingPriorityLabel(),
            serviceMark: $parcelValue->getServiceMark(),
            serviceName: $parcelValue->getServiceName(),
            signaletiqueProduit: $parcelValue->getSignaletiqueProduit(),
            reservationNumber: $parcelValue->getReservationNumber(),
            dSort: $parcelValue->getDSort(),
            oSort: $parcelValue->getOSort(),
        );
    }

    /**
     * Get Esd info from result.
     *
     * @param \ChronopostShipping\StructType\ResultReservationMultiParcelExpeditionValue $result
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\EsdInfo|null
     */
    private function mapToEsdInfo(
        ResultReservationMultiParcelExpeditionValue $result
    ): EsdInfo|null {
        $esdFullNumber = $result->getESDFullNumber();
        $esdNumber     = $result->getESDNumber();
        $pickupDate    = $result->getPickupDate();

        if ($esdFullNumber !== null && $esdNumber !== null && $pickupDate !== null) {
            $parsedDate = \DateTimeImmutable::createFromFormat(
                'Y-m-d\TH:i:s',
                $pickupDate
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
