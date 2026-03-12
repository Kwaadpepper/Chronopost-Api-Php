<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\Shipping;

use Kwaadpepper\ChronopostApiPhp\Dto\Dto;

readonly class SkybillLabel implements Dto
{
    /**
     * @param string                                                     $skybillNumber
     * @param \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\TransportTicket $transportTicket
     * @param string|null                                                $type
     */
    public function __construct(
        public string $skybillNumber,
        public TransportTicket $transportTicket,
        public ?string $type = null,
    ) {
    }
}
