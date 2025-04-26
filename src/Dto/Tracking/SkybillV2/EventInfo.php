<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SkybillV2;

use Kwaadpepper\ChronopostApiPhp\Dto\Dto;

readonly class EventInfo implements Dto
{
    /**
     * Constructor
     *
     * @param string                                                       $code
     * @param \DateTimeImmutable                                           $date
     * @param string                                                       $label
     * @param boolean                                                      $highPriority
     * @param string|null                                                  $npc
     * @param string|null                                                  $officeLabel
     * @param string|null                                                  $zipCode
     * @param \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SkybillV2\Event[] $events
     * @throws \InvalidArgumentException Exception if $events is not an array of TrackingSkybillEvent.
     * @phpstan-ignore throws.unusedType
     */
    public function __construct(
        public string $code,
        public \DateTimeImmutable $date,
        public string $label,
        public bool $highPriority = false,
        public ?string $npc = null,
        public ?string $officeLabel = null,
        public ?string $zipCode = null,
        protected array $events = []
    ) {
        foreach ($events as $event) {
            // @phpstan-ignore instanceof.alwaysTrue
            if (!$event instanceof Event) {
                throw new \InvalidArgumentException('Events must be an array of ' . Event::class);
            }
        }
    }

    /**
     * Get the events
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SkybillV2\Event[]
     */
    public function getEvents(): array
    {
        return $this->events;
    }
}
