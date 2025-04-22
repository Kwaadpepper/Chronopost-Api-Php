<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto;

class TrackingSkybillEventInfo implements Dto
{
    /**
     * Constructor
     *
     * @param string                 $code
     * @param \DateTimeImmutable     $date
     * @param string                 $label
     * @param boolean                $highPriority
     * @param string|null            $npc
     * @param string|null            $officeLabel
     * @param string|null            $zipCode
     * @param TrackingSkybillEvent[] $events
     * @throws \InvalidArgumentException Exception if $events is not an array of TrackingSkybillEvent.
     * @phpstan-ignore throws.unusedType
     */
    public function __construct(
        protected string $code,
        protected \DateTimeImmutable $date,
        protected string $label,
        protected bool $highPriority = false,
        protected ?string $npc = null,
        protected ?string $officeLabel = null,
        protected ?string $zipCode = null,
        protected array $events = []
    ) {
        foreach ($events as $event) {
            // @phpstan-ignore instanceof.alwaysTrue
            if (!$event instanceof TrackingSkybillEvent) {
                throw new \InvalidArgumentException('Events must be an array of TrackingSkybillEvent.');
            }
        }
        $this->events = $events;
    }
}
