<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Contracts;

use ChronopostShipping\StructType\RecipientValue as RecipientValueChronopost;
use ChronopostShipping\StructType\ShipperValue as ShipperValueChronopost;
use ChronopostShipping\StructType\SkybillValueBase;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\RoutingInfo;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\ShippingInformation;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\SkybillLabel;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\RoutingQuery;

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
        RoutingQuery $query,
    ): RoutingInfo;

    public function getShippingInformation(
        ShipperValueChronopost $shipperValue,
        RecipientValueChronopost $recipientValue,
        SkybillValueBase $skybillValueBase,
    ): ShippingInformation;
}
