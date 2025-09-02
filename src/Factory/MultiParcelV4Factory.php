<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Factory;

use ChronopostShipping\StructType\ResultMultiParcelExpeditionValue;
use ChronopostShipping\StructType\ResultMultiParcelValue;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\EsdInfo;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\MultiParcelV4;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\MultiParcelValue;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\TransportTicket;
use Kwaadpepper\ChronopostApiPhp\Factory\Factory;

class MultiParcelV4Factory implements Factory
{
    /**
     * Create a new instance of MultiParcelV4.
     *
     * @param \ChronopostShipping\StructType\ResultMultiParcelExpeditionValue $result
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\MultiParcelV4
     * @phpcs:disable Squiz.Commenting.FunctionComment.TypeHintMissing
     */
    public function create($result): MultiParcelV4
    {
        $resultMultiParcel = $result->getResultMultiParcelValue();
        $multiParcelValues = array_map(
            fn(ResultMultiParcelValue $parcelValue) => $this->mapToMultiParcelValue($parcelValue),
            $resultMultiParcel
        );
        $reservationNumber = $result->getReservationNumber();
        $esdInfo           = $this->mapToEsdInfo($result);

        return new MultiParcelV4(
            $multiParcelValues,
            $reservationNumber,
            $esdInfo
        );
    }

    /**
     * Summary of mapToMultiParcelValue
     *
     * @param \ChronopostShipping\StructType\ResultMultiParcelValue $parcelValue
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\MultiParcelValue
     * @throws \InvalidArgumentException If the PDF Etiquette is null.
     */
    private function mapToMultiParcelValue(ResultMultiParcelValue $parcelValue): MultiParcelValue
    {
        $asCode                = $parcelValue->getAsCode();
        $codeDepot             = $parcelValue->getCodeDepot();
        $codeService           = $parcelValue->getCodeService();
        $destinationDepot      = $parcelValue->getDestinationDepot();
        $geoPostCodeBarre      = $parcelValue->getGeoPostCodeBarre();
        $geoPostNumeroColis    = $parcelValue->getGeoPostNumeroColis();
        $groupingPriorityLabel = $parcelValue->getGroupingPriorityLabel();
        $serviceMark           = $parcelValue->getServiceMark();
        $serviceName           = $parcelValue->getServiceName();
        $signaletiqueProduit   = $parcelValue->getSignaletiqueProduit();
        $skybillNumber         = $parcelValue->getSkybillNumber();
        $dSort                 = $parcelValue->getDSort();
        $oSort                 = $parcelValue->getOSort();

        $pdfEtiquette = $parcelValue->getPdfEtiquette();

        if ($pdfEtiquette === null) {
            throw new \InvalidArgumentException('PDF Etiquette is null');
        }
        if (!$this->isBase64($pdfEtiquette)) {
            $pdfEtiquette = base64_encode($pdfEtiquette);
        }

        $transportTicket = new TransportTicket($pdfEtiquette);

        return new MultiParcelValue(
            $asCode,
            $codeDepot,
            $codeService,
            $destinationDepot,
            $geoPostCodeBarre,
            $geoPostNumeroColis,
            $groupingPriorityLabel,
            $transportTicket,
            $serviceMark,
            $serviceName,
            $signaletiqueProduit,
            $skybillNumber,
            $dSort,
            $oSort,
        );
    }

    /**
     * Get Esd info from result
     *
     * @param \ChronopostShipping\StructType\ResultMultiParcelExpeditionValue $result
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\EsdInfo|null
     */
    private function mapToEsdInfo(ResultMultiParcelExpeditionValue $result): EsdInfo|null
    {
        $esdFullNumber = $result->getESDFullNumber();
        $esdNumber     = $result->getESDNumber();
        $pickupDate    = $result->getPickupDate();

        if ($esdFullNumber !== null && $esdNumber !== null && $pickupDate !== null) {
            return new EsdInfo(
                $esdFullNumber,
                $esdNumber,
                \DateTimeImmutable::createFromFormat(
                    'Y-m-d\TH:i:s',
                    $pickupDate
                )
            );
        }

        return null;
    }

    /**
     * Check if a string is base64 encoded.
     *
     * @param string $input
     * @return boolean
     */
    private function isBase64(string $input): bool
    {
        if ($input === '' || strlen($input) % 4 !== 0) {
            return false;
        }

        if (!preg_match('/^[A-Za-z0-9+\/]*={0,2}$/', $input)) {
            return false;
        }

        $decoded = base64_decode($input, true);
        return $decoded !== false && base64_encode($decoded) === $input;
    }
}
