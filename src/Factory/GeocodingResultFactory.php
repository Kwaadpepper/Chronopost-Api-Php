<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Factory;

use Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot\GeocodingResult;

class GeocodingResultFactory implements Factory
{
    /**
     * Create a new instance of the factory.
     */
    public function __construct()
    {
    }

    /** @param \ChronopostTimeSlot\StructType\GeocodageResponse $response */
    public function create($response): GeocodingResult
    {
        return new GeocodingResult(
            $response->getLat(),
            $response->getLon(),
            $response->getNiveauQualite(),
        );
    }
}
