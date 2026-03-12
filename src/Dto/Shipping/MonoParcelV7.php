<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\Shipping;

use Kwaadpepper\ChronopostApiPhp\Dto\Dto;

readonly class MonoParcelV7 implements Dto
{
    /**
     * MonoParcelV7
     *
     * @param string                                                     $skybillNumber         The skybill number.
     * @param \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\TransportTicket $transportTicket       The transport ticket (PDF).
     * @param string|null                                                $codeDepot             The depot code.
     * @param string|null                                                $codeService           The service code.
     * @param string|null                                                $destinationDepot      The destination depot.
     * @param string|null                                                $geoPostCodeBarre      The GeoPost barcode.
     * @param string|null                                                $geoPostNumeroColis    The GeoPost parcel number.
     * @param string|null                                                $groupingPriorityLabel The grouping priority label.
     * @param string|null                                                $serviceMark           The service mark.
     * @param string|null                                                $serviceName           The service name.
     * @param string|null                                                $signaletiqueProduit   The product signage.
     * @param string|null                                                $dSort                 The D sort.
     * @param string|null                                                $oSort                 The O sort.
     * @param \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\EsdInfo|null    $esdInfo               ESD information, if available.
     */
    public function __construct(
        public string $skybillNumber,
        public TransportTicket $transportTicket,
        public string|null $codeDepot = null,
        public string|null $codeService = null,
        public string|null $destinationDepot = null,
        public string|null $geoPostCodeBarre = null,
        public string|null $geoPostNumeroColis = null,
        public string|null $groupingPriorityLabel = null,
        public string|null $serviceMark = null,
        public string|null $serviceName = null,
        public string|null $signaletiqueProduit = null,
        public string|null $dSort = null,
        public string|null $oSort = null,
        public EsdInfo|null $esdInfo = null,
    ) {
    }
}
