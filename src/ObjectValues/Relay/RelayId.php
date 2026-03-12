<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay;

class RelayId
{
    public function __construct(
        public readonly string $id,
    ) {
    }
}
