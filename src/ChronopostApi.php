<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp;

use Kwaadpepper\ChronopostApiPhp\Dto\QuickCostV3;
use Kwaadpepper\ChronopostApiPhp\Enums\ShippingType;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ProductCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingNumber;
use Kwaadpepper\ChronopostApiPhp\Services\Cost\QuickCostService;
use Kwaadpepper\ChronopostApiPhp\Services\Tracking\TrackSearchService;
use WsdlToPhp\PackageBase\SoapClientInterface;

class ChronopostApi
{
    private TrackSearchService $trackSearchService;

    private QuickCostService $quickCostService;


    /**
     * Constructor
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber $accountNumber The account number.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Password      $password      The password.
     */
    public function __construct(
        #[\SensitiveParameter] private AccountNumber $accountNumber,
        #[\SensitiveParameter] private Password $password
    ) {
        $defaultSopapOptions = [
            SoapClientInterface::WSDL_LOGIN => $accountNumber->getAccountNumber(),
            SoapClientInterface::WSDL_PASSWORD => $password->getPassword(),
        ];

        $this->trackSearchService = new TrackSearchService($defaultSopapOptions);
        $this->quickCostService   = new QuickCostService($defaultSopapOptions);
    }

    /**
     * Track a single shipment using the tracking number.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\TrackingNumber $trackingNumber The tracking number to search.
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\SkybillV2\EventInfo[] The tracking information.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError          If the API call fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\TrackingException If the tracking number is invalid
     *                                                                    or if there are no events found.
     */
    public function trackSingleShipment(TrackingNumber $trackingNumber): array
    {
        return $this->trackSearchService->findUsingTrackingNumber($trackingNumber);
    }

    /**
     * Estimate the shipping cost for a shipment.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode    $from          The sender's postal code.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\PostCode    $to            The recipient's postal code.
     * @param integer                                                $weightInGrams The weight of the shipment in grams.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\ProductCode $productCode   The product code for the shipment.
     * @param \Kwaadpepper\ChronopostApiPhp\Enums\ShippingType       $shippingType  The shipping type.
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\QuickCostV3 The estimated shipping cost.
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError                     If the API call fails.
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\QuickCost\QuickCostException If the API returns an error.
     */
    public function estimateShippingCost(
        PostCode $from,
        PostCode $to,
        int $weightInGrams,
        ProductCode $productCode,
        ShippingType $shippingType
    ): QuickCostV3 {
        return $this->quickCostService->quickCostV3(
            $this->accountNumber,
            $this->password,
            $from,
            $to,
            $weightInGrams / 1000,
            $productCode,
            $shippingType
        );
    }
}
