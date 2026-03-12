<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Factory;

use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\ShippingInformation;

class ShippingInformationFactory implements Factory
{
    // phpcs:disable Squiz.Commenting.FunctionComment.TypeHintMissing
    /**
     * @param \ChronopostShipping\StructType\ResultShippingInfo $response
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\ShippingInformation
     * @throws \InvalidArgumentException If shipping information is missing.
     */
    public function create($response): ShippingInformation
    {
        $shippingInfo = $response->getShippingInfo();

        if ($shippingInfo === null) {
            throw new \InvalidArgumentException('Shipping information is missing');
        }

        return new ShippingInformation(
            asCode: $shippingInfo->getAsCode(),
            codeService: $shippingInfo->getCodeService(),
            destinationDepot: $shippingInfo->getDestinationDepot(),
            groupingPriorityLabel: $shippingInfo->getGroupingPriorityLabel(),
            serviceMark: $shippingInfo->getServiceMark(),
            serviceName: $shippingInfo->getServiceName(),
            signaletiqueProduit: $shippingInfo->getSignaletiqueProduit(),
            dSort: $shippingInfo->getDSort(),
            oSort: $shippingInfo->getOSort(),
        );
    }
    // phpcs:enable
}
