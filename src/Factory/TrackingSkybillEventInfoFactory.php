<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Factory;

use ChronopostTracking\StructType\InfoComp;
use DateTimeImmutable;
use Kwaadpepper\ChronopostApiPhp\Dto\TrackingSkybillEvent;
use Kwaadpepper\ChronopostApiPhp\Dto\TrackingSkybillEventInfo;

class TrackingSkybillEventInfoFactory implements Factory
{
    /**
     * Create a new instance of TrackingSkybillEventInfo.
     *
     * @param \ChronopostTracking\StructType\EventInfoComp $result
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\TrackingSkybillEventInfo
     * @phpcs:disable Squiz.Commenting.FunctionComment.TypeHintMissing
     */
    public function create($result)
    {
        // phpcs:enable
        $code         = $result->getCode();
        $date         = $result->getEventDate();
        $label        = $result->getEventLabel();
        $highPriority = (bool)$result->getHighPriority();
        $npc          = $result->getNpc();
        $officeLabel  = $result->getOfficeLabel();
        $zipCode      = $result->getZipCode();
        $events       = [];
        foreach ($result->getInfoCompList() as $listEventComp) {
            $events[] = $this->infoCompToTrackingSkybillEventInfo($listEventComp);
        }

        return new TrackingSkybillEventInfo(
            code: $code,
            date: new DateTimeImmutable($date),
            label: $label,
            highPriority: $highPriority,
            npc: $npc,
            officeLabel: $officeLabel,
            zipCode: $zipCode,
            events: $events
        );
    }

    /**
     * Convert a list of EventInfoComp to TrackingSkybillEvent.
     *
     * @param \ChronopostTracking\StructType\InfoComp $infoComp
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\TrackingSkybillEvent
     */
    private function infoCompToTrackingSkybillEventInfo(
        InfoComp $infoComp
    ): TrackingSkybillEvent {
        $name  = $infoComp->getName();
        $value = $infoComp->getValue();

        return new TrackingSkybillEvent(value: $value, name: $name);
    }
}
