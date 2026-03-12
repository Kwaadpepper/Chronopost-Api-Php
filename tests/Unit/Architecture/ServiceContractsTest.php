<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Tests\Unit\Architecture;

use Kwaadpepper\ChronopostApiPhp\Contracts\CalculateServiceInterface;
use Kwaadpepper\ChronopostApiPhp\Contracts\QuickCostServiceInterface;
use Kwaadpepper\ChronopostApiPhp\Contracts\RelaySearchServiceInterface;
use Kwaadpepper\ChronopostApiPhp\Contracts\ShippingServiceInterface;
use Kwaadpepper\ChronopostApiPhp\Contracts\TrackingServiceInterface;
use Kwaadpepper\ChronopostApiPhp\Services\Calculate\CalculateService;
use Kwaadpepper\ChronopostApiPhp\Services\Cost\QuickCostService;
use Kwaadpepper\ChronopostApiPhp\Services\RelayPoint\RelayPointService;
use Kwaadpepper\ChronopostApiPhp\Services\Shipping\ShippingService;
use Kwaadpepper\ChronopostApiPhp\Services\Tracking\TrackSearchService;
use PHPUnit\Framework\TestCase;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.Missing
 */
class ServiceContractsTest extends TestCase
{
    public function testGivenServiceClassesWhenCheckingImplementedInterfacesThenEveryServiceImplementsItsContract(): void
    {
        // GIVEN.
        $expectedContracts = [
            TrackSearchService::class => TrackingServiceInterface::class,
            ShippingService::class => ShippingServiceInterface::class,
            RelayPointService::class => RelaySearchServiceInterface::class,
            CalculateService::class => CalculateServiceInterface::class,
            QuickCostService::class => QuickCostServiceInterface::class,
        ];

        // WHEN.
        foreach ($expectedContracts as $serviceClass => $contractClass) {
            // THEN.
            $this->assertTrue(interface_exists($contractClass), sprintf('Contract %s must exist.', $contractClass));

            /** @var string[] $implementedInterfaces */
            $implementedInterfaces = class_implements($serviceClass) ?: [];

            $this->assertContains(
                $contractClass,
                $implementedInterfaces,
                sprintf('Service %s must implement %s.', $serviceClass, $contractClass),
            );
        }
    }
}
