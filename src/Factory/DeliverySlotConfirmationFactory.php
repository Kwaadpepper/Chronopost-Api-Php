<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Factory;

use Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot\DeliverySlotConfirmation;

class DeliverySlotConfirmationFactory implements Factory
{
    /**
     * Create a new instance of the factory.
     */
    public function __construct()
    {
    }

    /** @param \ChronopostTimeSlot\StructType\ServiceResponseV2 $response */
    public function create($response): DeliverySlotConfirmation
    {
        $productServiceV2 = $response->getProductServiceV2();

        return new DeliverySlotConfirmation(
            $response->getCode(),
            $response->getMessage(),
            $productServiceV2?->getProductCode(),
            $productServiceV2?->getServiceCode(),
            $productServiceV2?->getAsCode(),
        );
    }
}
