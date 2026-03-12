<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Contracts;

use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\MultiParcelV4;
use Kwaadpepper\ChronopostApiPhp\Enums\SkyBillOutputMode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\CustomerValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\EsdValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\RecipientValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ReferenceValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ScheduledValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ShipperValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\SkyBillParameters;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\SkyBillValue;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;

interface ShippingServiceInterface
{
    public function singleParcelV4(
        AccountNumber $accountNumber,
        Password $password,
        SkyBillValue $skybillValue,
        CustomerValue $customerValue,
        ShipperValue $shipperValue,
        RecipientValue $recipientValue,
        ReferenceValue $referenceValue,
        ?ScheduledValue $scheduledValue = null,
        ?EsdValue $esdValue = null,
        SkyBillOutputMode $skyBillOutputMode = SkyBillOutputMode::NO_MAIL_SENDING,
        ?SkyBillParameters $skyBillParameters = null
    ): MultiParcelV4;

    public function multiParcelV4(
        AccountNumber $accountNumber,
        Password $password,
        array $skybillValues,
        CustomerValue $customerValue,
        array $shippersValues,
        array $recipientsValues,
        array $referenceValues = [],
        array $scheduledValues = [],
        ?EsdValue $esdValue = null,
        int $numberOfParcel = 1,
        bool $multiParcel = false,
        SkyBillOutputMode $skyBillOutputMode = SkyBillOutputMode::NO_MAIL_SENDING,
        ?SkyBillParameters $skyBillParameters = null
    ): MultiParcelV4;
}
