<?php

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues;

use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ReferenceValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ScheduledValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\SkyBillValue;

class MultiParcelPart
{
    /**
     * Part of a multiparcel call.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\SkyBillValue        $skybillValue   The skybill value.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ReferenceValue      $referenceValue The reference value.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ScheduledValue|null $scheduledValue The scheduled value.
     */
    public function __construct(
        public readonly SkyBillValue $skybillValue,
        public readonly ReferenceValue $referenceValue,
        public readonly ?ScheduledValue $scheduledValue = null
    ) {
    }
}
