<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Contracts;

use ChronopostShipping\StructType\RecipientValue as RecipientValueChronopost;
use ChronopostShipping\StructType\ShipperValue as ShipperValueChronopost;
use ChronopostShipping\StructType\SkybillValueBase;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\RoutingInfo;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\ShippingInformation;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\SkybillLabel;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;

interface ShippingLabelServiceInterface
{
    public function getSkybill(
        AccountNumber $accountNumber,
        Password $password,
        string $numberSearch,
        string $mode = 'PDF',
        ?string $key = null,
    ): SkybillLabel;

    public function getReservedSkybill(
        AccountNumber $accountNumber,
        Password $password,
        string $reservationNumber,
    ): SkybillLabel;

    public function getReservedSkybillWithType(
        AccountNumber $accountNumber,
        Password $password,
        string $reservationNumber,
    ): SkybillLabel;

    public function getReservedSkybillWithTypeAndMode(
        AccountNumber $accountNumber,
        Password $password,
        string $reservationNumber,
        string $mode,
    ): SkybillLabel;

    public function getReservedSkybillWithTypeAndModeAuth(
        AccountNumber $accountNumber,
        Password $password,
        string $numberSearch,
        string $mode,
    ): SkybillLabel;

    public function getReservedSkybillWithTypeAndModeByReservation(
        AccountNumber $accountNumber,
        Password $password,
        string $reservationNumber,
        string $mode,
    ): SkybillLabel;

    public function getRouting(
        AccountNumber $accountNumber,
        Password $password,
        string $shipperDepot,
        string $countryCode,
        string $zipCode,
        ?string $socode = null,
        ?string $ascode = null,
    ): RoutingInfo;

    public function getShippingInformation(
        AccountNumber $accountNumber,
        Password $password,
        ShipperValueChronopost $shipperValue,
        RecipientValueChronopost $recipientValue,
        SkybillValueBase $skybillValueBase,
    ): ShippingInformation;
}
