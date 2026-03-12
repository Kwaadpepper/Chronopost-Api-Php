<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Contracts;

use ChronopostShipping\StructType\RecipientValue as RecipientValueChronopost;
use ChronopostShipping\StructType\ShipperValue as ShipperValueChronopost;
use ChronopostShipping\StructType\SkybillValueBase;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\RoutingInfo;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\ShippingInformation;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\SkybillLabel;

interface ShippingLabelServiceInterface
{
    public function getSkybill(
        string $numberSearch,
        string $mode = 'PDF',
        ?string $key = null,
    ): SkybillLabel;

    public function getReservedSkybill(
        string $reservationNumber,
    ): SkybillLabel;

    public function getReservedSkybillWithType(
        string $reservationNumber,
    ): SkybillLabel;

    public function getReservedSkybillWithTypeAndMode(
        string $reservationNumber,
        string $mode,
    ): SkybillLabel;

    public function getReservedSkybillWithTypeAndModeAuth(
        string $numberSearch,
        string $mode,
    ): SkybillLabel;

    public function getReservedSkybillWithTypeAndModeByReservation(
        string $reservationNumber,
        string $mode,
    ): SkybillLabel;

    public function getRouting(
        string $shipperDepot,
        string $countryCode,
        string $zipCode,
        ?string $socode = null,
        ?string $ascode = null,
    ): RoutingInfo;

    public function getShippingInformation(
        ShipperValueChronopost $shipperValue,
        RecipientValueChronopost $recipientValue,
        SkybillValueBase $skybillValueBase,
    ): ShippingInformation;
}
