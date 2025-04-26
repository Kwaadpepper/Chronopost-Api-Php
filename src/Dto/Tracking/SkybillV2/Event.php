<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SkybillV2;

use Kwaadpepper\ChronopostApiPhp\Dto\Dto;

readonly class Event implements Dto
{
    /**
     * Constructor
     *
     * @param string $name
     * @param string $value
     */
    public function __construct(
        public string $name,
        public string $value,
    ) {
    }
}
