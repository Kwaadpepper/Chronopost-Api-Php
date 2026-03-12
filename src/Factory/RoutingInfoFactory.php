<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Factory;

use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\RoutingInfo;

class RoutingInfoFactory implements Factory
{
    /**
     * @param \ChronopostShipping\StructType\ResultGetRouting $response
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\RoutingInfo
     */
    public function create($response): RoutingInfo
    {
        return new RoutingInfo(
            posteComptable: $response->getPosteComptable(),
            geopostData: $this->mapGeopostData($response->getGeopostResult()),
        );
    }

    /**
     * @param \ChronopostShipping\StructType\GeopostResult|null $geopost
     * @return array<string, string|null>
     */
    private function mapGeopostData(?\ChronopostShipping\StructType\GeopostResult $geopost): array
    {
        $map = [
            'barcodeId' => 'getBarcodeId',
            'barcodePostcode' => 'getBarcodePostcode',
            'buAlphaString' => 'getBuAlphaString',
            'buCode' => 'getBuCode',
            'cSort' => 'getCSort',
            'dCountry' => 'getDCountry',
            'dDepot' => 'getDDepot',
            'dDepotCountry' => 'getDDepotCountry',
            'dDepotStr' => 'getDDepotStr',
            'dSort' => 'getDSort',
            'groupingPriority' => 'getGroupingPriority',
            'networkCode' => 'getNetworkCode',
            'oSort' => 'getOSort',
            'partnerCode' => 'getPartnerCode',
            'sSort' => 'getSSort',
            'serviceMark' => 'getServiceMark',
            'serviceText' => 'getServiceText',
            'version' => 'getVersion',
        ];

        $data = [];

        foreach ($map as $key => $getter) {
            $data[$key] = $geopost?->{$getter}();
        }

        return $data;
    }
}
