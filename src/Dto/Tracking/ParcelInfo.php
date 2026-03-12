<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\Tracking;

use Kwaadpepper\ChronopostApiPhp\Dto\Dto;

readonly class ParcelInfo implements Dto
{
    /**
     * @param string      $skybillNumber
     * @param string|null $dateDeposit
     * @param string|null $depositCountry
     * @param string|null $depositZipCode
     * @param string|null $objectType
     * @param string|null $recipientCity
     * @param string|null $recipientCountry
     * @param string|null $recipientName
     * @param string|null $recipientRef
     * @param string|null $recipientZipCode
     * @param string|null $shipperCity
     * @param string|null $shipperRef
     * @param string|null $shipperZipCode
     * @param string|null $significantEventCode
     * @param string|null $significantEventDate
     * @param string|null $significantEventLabel
     */
    public function __construct(
        public string $skybillNumber,
        public ?string $dateDeposit,
        public ?string $depositCountry,
        public ?string $depositZipCode,
        public ?string $objectType,
        public ?string $recipientCity,
        public ?string $recipientCountry,
        public ?string $recipientName,
        public ?string $recipientRef,
        public ?string $recipientZipCode,
        public ?string $shipperCity,
        public ?string $shipperRef,
        public ?string $shipperZipCode,
        public ?string $significantEventCode,
        public ?string $significantEventDate,
        public ?string $significantEventLabel,
    ) {
    }
}
