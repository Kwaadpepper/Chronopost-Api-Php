<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\QuickCost;

use Kwaadpepper\ChronopostApiPhp\Enums\ChronopostProductCode;

class Product
{
    public readonly ChronopostProductCode $code;

    public function __construct(
        public string $originalCode,
    ) {
        $this->code = $this->toChronopostProductCode($originalCode);
    }

    private function toChronopostProductCode(string $code): ChronopostProductCode
    {
        return ChronopostProductCode::tryFromOrUnknown($code);
    }
}
