<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Factory;

use ChronopostTracking\StructType\InfoComp;
use DateTimeImmutable;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SkybillV2\Event;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SkybillV2\EventInfo;

class TrackingSkybillV2EventFactory implements Factory
{
    /**
     * Create a new instance of TrackingSkybillEventInfo.
     *
     * @param \ChronopostTracking\StructType\EventInfoComp $result
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SkybillV2\EventInfo
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
        $events       = array_map(
            fn (InfoComp $infoComp) => $this->infoCompToTrackingSkybillEventInfo($infoComp),
            $result->getInfoCompList() ?? []
        );

        return new EventInfo(
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
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SkybillV2\Event
     */
    private function infoCompToTrackingSkybillEventInfo(
        InfoComp $infoComp
    ): Event {
        $name  = $infoComp->getName();
        $value = $infoComp->getValue();

        return new Event(value: $value, name: $name);
    }
}
