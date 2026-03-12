<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\Tracking;

use Kwaadpepper\ChronopostApiPhp\Dto\Dto;

readonly class ParcelProofOfDelivery implements Dto
{
    /**
     * @param string      $skybillNumber
     * @param boolean     $podPresent
     * @param string|null $format
     * @param string|null $podData
     * @param integer     $statusCode
     */
    public function __construct(
        public string $skybillNumber,
        public bool $podPresent,
        public ?string $format,
        public ?string $podData,
        public int $statusCode,
    ) {
    }
}
