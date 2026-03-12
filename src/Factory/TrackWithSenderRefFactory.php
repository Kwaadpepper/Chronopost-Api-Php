<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Factory;

use ChronopostTracking\StructType\Event;
use ChronopostTracking\StructType\ListEvents;
use DateTimeImmutable;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\ParcelEvents;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SkybillV2\EventInfo;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SenderRefTrackResult;

class TrackWithSenderRefFactory implements Factory
{
    /**
     * Create a SenderRefTrackResult from a ResultTrackWithSenderRef.
     *
     * @param \ChronopostTracking\StructType\ResultTrackWithSenderRef $result
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SenderRefTrackResult
     * @phpcs:disable Squiz.Commenting.FunctionComment.TypeHintMissing
     */
    public function create($result)
    {
        // phpcs:enable
        $listParcels = $result->getListParcel() ?? [];

        $parcels = array_map(
            fn (ListEvents $listEvents) => $this->mapListEvents($listEvents),
            $listParcels,
        );

        return new SenderRefTrackResult(parcels: $parcels);
    }

    /**
     * @param \ChronopostTracking\StructType\ListEvents $listEvents
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\ParcelEvents
     */
    private function mapListEvents(ListEvents $listEvents): ParcelEvents
    {
        $events = array_map(
            fn (Event $event) => $this->mapEvent($event),
            $listEvents->getEvents() ?? [],
        );

        return new ParcelEvents(
            skybillNumber: (string) $listEvents->getSkybillNumber(),
            events: $events,
        );
    }

    /**
     * @param \ChronopostTracking\StructType\Event $event
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SkybillV2\EventInfo
     */
    private function mapEvent(Event $event): EventInfo
    {
        return new EventInfo(
            code: (string) $event->getCode(),
            date: new DateTimeImmutable((string) $event->getEventDate()),
            label: (string) $event->getEventLabel(),
            highPriority: (bool) $event->getHighPriority(),
            npc: $event->getNPC(),
            officeLabel: $event->getOfficeLabel(),
            zipCode: $event->getZipCode(),
        );
    }
}
