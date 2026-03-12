<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\Tracking;

use Kwaadpepper\ChronopostApiPhp\Dto\Dto;

readonly class CancelResult implements Dto
{
    /**
     * @param integer $errorCode
     * @param string  $errorMessage
     * @param integer $statusCode
     */
    public function __construct(
        public int $errorCode,
        public string $errorMessage,
        public int $statusCode,
    ) {
    }
}
