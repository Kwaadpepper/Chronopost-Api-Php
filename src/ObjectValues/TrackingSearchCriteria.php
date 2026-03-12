<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues;

use Kwaadpepper\ChronopostApiPhp\Enums\CountryForChronopost;
use Kwaadpepper\ChronopostApiPhp\Enums\ParcelState;

/**
 * Composite criteria for tracking search (trackSearch / trackBySearchQuery).
 */
readonly class TrackingSearchCriteria
{
    public function __construct(
        private ?CountryForChronopost $consigneesCountry = null,
        private ?SenderReference $consigneesRef = null,
        private ?PostCode $consigneesPostCode = null,
        private ?DateRange $depositDateRange = null,
        private ?ParcelState $parcelState = null,
        private ?SenderReference $sendersRef = null,
        private ?ServiceCode $serviceCode = null,
    ) {
    }

    public function getConsigneesCountry(): ?CountryForChronopost
    {
        return $this->consigneesCountry;
    }

    public function getConsigneesRef(): ?SenderReference
    {
        return $this->consigneesRef;
    }

    public function getConsigneesPostCode(): ?PostCode
    {
        return $this->consigneesPostCode;
    }

    public function getDepositDateRange(): ?DateRange
    {
        return $this->depositDateRange;
    }

    public function getParcelState(): ?ParcelState
    {
        return $this->parcelState;
    }

    public function getSendersRef(): ?SenderReference
    {
        return $this->sendersRef;
    }

    public function getServiceCode(): ?ServiceCode
    {
        return $this->serviceCode;
    }
}
