<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Factory;

use ChronopostTracking\StructType\InfosPOD;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\ParcelInfo;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SearchTrackResult;

class TrackSearchResultFactory implements Factory
{
    /**
     * Create a SearchTrackResult from a ResultTrackSearch.
     *
     * @param \ChronopostTracking\StructType\ResultTrackSearch $result
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SearchTrackResult
     * @phpcs:disable Squiz.Commenting.FunctionComment.TypeHintMissing
     */
    public function create($result)
    {
        // phpcs:enable
        $infosPods = $result->getListInfosPOD() ?? [];

        $parcels = array_map(
            fn (InfosPOD $info) => $this->mapInfosPod($info),
            $infosPods,
        );

        return new SearchTrackResult(parcels: $parcels);
    }

    /**
     * @param \ChronopostTracking\StructType\InfosPOD $info
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\ParcelInfo
     */
    private function mapInfosPod(InfosPOD $info): ParcelInfo
    {
        $event = $info->getSignificantEvent();

        return new ParcelInfo(
            skybillNumber: (string)$info->getSkybillNumber(),
            dateDeposit: $info->getDateDeposit(),
            depositCountry: $info->getDepositCountry(),
            depositZipCode: $info->getDepositZipCode(),
            objectType: $info->getObjectType(),
            recipientCity: $info->getRecipientCity(),
            recipientCountry: $info->getRecipientCountry(),
            recipientName: $info->getRecipientName(),
            recipientRef: $info->getRecipientRef(),
            recipientZipCode: $info->getRecipientZipCode(),
            shipperCity: $info->getShipperCity(),
            shipperRef: $info->getShipperRef(),
            shipperZipCode: $info->getShipperZipCode(),
            significantEventCode: $event?->getCode(),
            significantEventDate: $event?->getEventDate(),
            significantEventLabel: $event?->getEventLabel(),
        );
    }
}
