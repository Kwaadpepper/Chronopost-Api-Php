<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\Tracking;

use Kwaadpepper\ChronopostApiPhp\Dto\Dto;
use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SkybillV2\EventInfo;

readonly class EsdTrackResult implements Dto
{
    /**
     * @param EventInfo[] $events
     *
     * @throws \InvalidArgumentException If $events contains non-EventInfo values.
     * @phpstan-ignore throws.unusedType
     */
    public function __construct(
        public array $events = [],
    ) {
        foreach ($events as $event) {
            // @phpstan-ignore instanceof.alwaysTrue
            if (!$event instanceof EventInfo) {
                throw new \InvalidArgumentException('Events must be an array of ' . EventInfo::class);
            }
        }
    }
}
