<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto;

class TrackingSkybillEvent implements Dto
{
    /**
     * Constructor
     *
     * @param string $name
     * @param string $value
     */
    public function __construct(
        protected string $name,
        protected string $value,
    ) {
    }
}
