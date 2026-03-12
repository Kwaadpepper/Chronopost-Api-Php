<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay;

class RelayId
{
    public function __construct(
        public readonly string $id,
    ) {
        if (trim($id) === '') {
            throw new \InvalidArgumentException('Relay point ID must not be empty');
        }
    }
}
