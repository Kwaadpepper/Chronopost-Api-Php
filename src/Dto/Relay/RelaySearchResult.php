<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\Relay;

use Kwaadpepper\ChronopostApiPhp\Dto\Dto;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay\RelayPointQualityResult;

class RelaySearchResult implements Dto
{
    public function __construct(
        public readonly RelayPointQualityResult $quality,
        /** @var RelayPoint[] $relayList */
        public readonly array $relayList,
    ) {
    }
}
