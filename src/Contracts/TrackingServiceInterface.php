<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Contracts;

use Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingV2Locale;

interface TrackingServiceInterface
{
    /**
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SkybillV2\EventInfo[]
     */
    public function findUsingTrackingNumber(TrackingNumber $trackingNumber, ?TrackingV2Locale $locale = null): array;
}
