<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\Tracking;

use Kwaadpepper\ChronopostApiPhp\Dto\Dto;

readonly class CancelListResult implements Dto
{
    /**
     * @param integer  $errorCode
     * @param string   $errorMessage
     * @param integer  $statusCode
     * @param string[] $skybills
     *
     * @throws \InvalidArgumentException If $skybills contains non-string values.
     */
    public function __construct(
        public int $errorCode,
        public string $errorMessage,
        public int $statusCode,
        public array $skybills = [],
    ) {
        foreach ($skybills as $skybill) {
            // @phpstan-ignore function.alreadyNarrowedType
            if (!is_string($skybill)) {
                throw new \InvalidArgumentException('Skybills must be an array of strings');
            }
        }
    }
}
