<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Factory;

use ChronopostShipping\StructType\ResultMonoParcelExpeditionValue;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\EsdInfo;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\MonoParcelV7;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\TransportTicket;

class MonoParcelV7Factory implements Factory
{
    /**
     * Create a new instance of MonoParcelV7.
     *
     * @param \ChronopostShipping\StructType\ResultMonoParcelExpeditionValue $result
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\MonoParcelV7
     *
     * @throws \InvalidArgumentException If the PDF etiquette or skybill number is missing.
     * @phpcs:disable Squiz.Commenting.FunctionComment.TypeHintMissing
     */
    public function create($result): MonoParcelV7
    {
        $pdfEtiquette  = $result->getPdfEtiquette();
        $skybillNumber = $result->getSkybillNumber();

        if ($pdfEtiquette === null) {
            throw new \InvalidArgumentException('PDF Etiquette is null');
        }

        if ($skybillNumber === null) {
            throw new \InvalidArgumentException('Skybill number is null');
        }

        if (!$this->isBase64($pdfEtiquette)) {
            $pdfEtiquette = base64_encode($pdfEtiquette);
        }

        $esdInfo = $this->mapToEsdInfo($result);

        return new MonoParcelV7(
            skybillNumber: $skybillNumber,
            transportTicket: new TransportTicket($pdfEtiquette),
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
            esdInfo: $esdInfo,
        );
    }

    /**
     * Get Esd info from result.
     *
     * @param \ChronopostShipping\StructType\ResultMonoParcelExpeditionValue $result
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\EsdInfo|null
     */
    private function mapToEsdInfo(ResultMonoParcelExpeditionValue $result): EsdInfo|null
    {
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

    /**
     * Check if a string is base64 encoded.
     *
     * @param string $input
     *
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
