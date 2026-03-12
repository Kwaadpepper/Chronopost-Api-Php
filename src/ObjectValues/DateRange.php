<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues;

readonly class DateRange
{
    private \DateTimeInterface $begin;
    private \DateTimeInterface $end;

    /**
     * @param \DateTimeInterface $begin Start of the range (must be ≤ end).
     * @param \DateTimeInterface $end   End of the range.
     */
    public function __construct(\DateTimeInterface $begin, \DateTimeInterface $end)
    {
        $this->validate($begin, $end);
        $this->begin = $begin;
        $this->end   = $end;
    }

    public function getBegin(): \DateTimeInterface
    {
        return $this->begin;
    }

    public function getEnd(): \DateTimeInterface
    {
        return $this->end;
    }

    private function validate(\DateTimeInterface $begin, \DateTimeInterface $end): void
    {
        if ($begin > $end) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Begin date (%s) must be before or equal to end date (%s).',
                    $begin->format('Y-m-d H:i:s'),
                    $end->format('Y-m-d H:i:s'),
                ),
            );
        }
    }
}
