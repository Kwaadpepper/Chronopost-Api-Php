<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp;

use Kwaadpepper\ChronopostApiPhp\Facade\CalculateFacade;
use Kwaadpepper\ChronopostApiPhp\Facade\DeliverySlotFacade;
use Kwaadpepper\ChronopostApiPhp\Facade\PickupFacade;
use Kwaadpepper\ChronopostApiPhp\Facade\RelayFacade;
use Kwaadpepper\ChronopostApiPhp\Facade\ShippingFacade;
use Kwaadpepper\ChronopostApiPhp\Facade\TrackingFacade;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;
use Kwaadpepper\ChronopostApiPhp\Services\Calculate\CalculateService;
use Kwaadpepper\ChronopostApiPhp\Services\Cost\QuickCostService;
use Kwaadpepper\ChronopostApiPhp\Services\DeliverySlot\DeliverySlotService;
use Kwaadpepper\ChronopostApiPhp\Services\RelayPoint\RelayPointService;
use Kwaadpepper\ChronopostApiPhp\Services\Shipping\PickupService;
use Kwaadpepper\ChronopostApiPhp\Services\Shipping\ShippingLabelService;
use Kwaadpepper\ChronopostApiPhp\Services\Shipping\ShippingService;
use Kwaadpepper\ChronopostApiPhp\Services\Tracking\ProofOfDeliveryService;
use Kwaadpepper\ChronopostApiPhp\Services\Tracking\TrackCancelService;
use Kwaadpepper\ChronopostApiPhp\Services\Tracking\TrackSearchService;
use WsdlToPhp\PackageBase\SoapClientInterface;

class ChronopostApi
{
    public readonly TrackingFacade $tracking;

    public readonly ShippingFacade $shipping;

    public readonly PickupFacade $pickup;

    public readonly RelayFacade $relay;

    public readonly DeliverySlotFacade $deliverySlot;

    public readonly CalculateFacade $calculate;

    /**
     * Constructor
     *
     * @param  \Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber $accountNumber The account number.
     * @param  \Kwaadpepper\ChronopostApiPhp\ObjectValues\Password      $password      The password.
     */
    public function __construct(
        #[\SensitiveParameter] AccountNumber $accountNumber,
        #[\SensitiveParameter] Password $password,
    ) {
        $defaultSopapOptions = [
            SoapClientInterface::WSDL_LOGIN    => $accountNumber->getAccountNumber(),
            SoapClientInterface::WSDL_PASSWORD => $password->getPassword(),
        ];

        $trackSearchService     = new TrackSearchService($accountNumber, $password, $defaultSopapOptions);
        $trackCancelService     = new TrackCancelService($accountNumber, $password, $defaultSopapOptions);
        $proofOfDeliveryService = new ProofOfDeliveryService($accountNumber, $password, $defaultSopapOptions);
        $shippingService        = new ShippingService($accountNumber, $password, $defaultSopapOptions);
        $shippingLabelService   = new ShippingLabelService($accountNumber, $password, $defaultSopapOptions);
        $calculateService       = new CalculateService($accountNumber, $password, $defaultSopapOptions);
        $quickCostService       = new QuickCostService($accountNumber, $password, $defaultSopapOptions);
        $relayPointService      = new RelayPointService($accountNumber, $password, $defaultSopapOptions);
        $pickupService          = new PickupService($accountNumber, $password, $defaultSopapOptions);
        $deliverySlotService    = new DeliverySlotService($accountNumber, $password, $defaultSopapOptions);

        $this->tracking     = new TrackingFacade($trackSearchService, $trackCancelService, $proofOfDeliveryService);
        $this->shipping     = new ShippingFacade($shippingService, $shippingLabelService);
        $this->pickup       = new PickupFacade($pickupService);
        $this->relay        = new RelayFacade($relayPointService);
        $this->deliverySlot = new DeliverySlotFacade($deliverySlotService);
        $this->calculate    = new CalculateFacade($calculateService, $quickCostService);
    }
}
