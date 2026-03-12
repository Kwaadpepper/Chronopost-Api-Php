<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Services\DeliverySlot;

use ChronopostTimeSlot\ClassMap;
use ChronopostTimeSlot\ServiceType\Confirm;
use ChronopostTimeSlot\ServiceType\Get;
use ChronopostTimeSlot\ServiceType\Search;
use ChronopostTimeSlot\StructType\ConfirmDeliverySlotV2;
use ChronopostTimeSlot\StructType\GetAdresseGeocodage;
use ChronopostTimeSlot\StructType\SearchDeliverySlot;
use Kwaadpepper\ChronopostApiPhp\Contracts\DeliverySlotServiceInterface;
use Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot\DeliverySlotConfirmation;
use Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot\DeliverySlotSearchResult;
use Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot\GeocodingResult;
use Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError;
use Kwaadpepper\ChronopostApiPhp\Exceptions\DeliverySlot\DeliverySlotException;
use Kwaadpepper\ChronopostApiPhp\Factory\DeliverySlotConfirmationFactory;
use Kwaadpepper\ChronopostApiPhp\Factory\DeliverySlotSearchResultFactory;
use Kwaadpepper\ChronopostApiPhp\Factory\GeocodingResultFactory;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\GeocodingAddress;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\SlotConfirmRequest;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\SlotSearchCriteria;
use WsdlToPhp\PackageBase\SoapClientInterface;

/**
 * Service for managing delivery time slots (créneaux de livraison).
 * Allows searching available slots, confirming a slot, and geocoding addresses.
 */
class DeliverySlotService implements DeliverySlotServiceInterface
{
    /**
     * @var \ChronopostTimeSlot\ServiceType\Search
     */
    private Search $searchService;

    /**
     * @var \ChronopostTimeSlot\ServiceType\Confirm
     */
    private Confirm $confirmService;

    /**
     * @var \ChronopostTimeSlot\ServiceType\Get
     */
    private Get $getService;

    /**
     * @var string
     */
    protected static string $serviceUrl = 'https://ws.chronopost.fr/rdv-cxf/services/CreneauServiceWS?wsdl';

    /**
     * Constructor
     *
     * @param array<string, mixed> $soapOptions Additional options for the soap client.
     */
    public function __construct(
        #[\SensitiveParameter] private AccountNumber $accountNumber,
        #[\SensitiveParameter] private Password $password,
        array $soapOptions = [],
        ?Search $searchService = null,
        ?Confirm $confirmService = null,
        ?Get $getService = null,
    ) {
        if ($searchService !== null && $confirmService !== null && $getService !== null) {
            $this->searchService  = $searchService;
            $this->confirmService = $confirmService;
            $this->getService     = $getService;
            return;
        }

        $soapOptions = array_merge(
            $soapOptions,
            [
                SoapClientInterface::WSDL_URL => static::$serviceUrl,
                SoapClientInterface::WSDL_CLASSMAP => ClassMap::get(),
            ],
        );

        $this->searchService  = new Search($soapOptions);
        $this->confirmService = new Confirm($soapOptions);
        $this->getService     = new Get($soapOptions);
    }

    /**
     * Search for available delivery time slots.
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot\DeliverySlotSearchResult
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\DeliverySlot\DeliverySlotException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     */
    public function searchDeliverySlots(
        SlotSearchCriteria $criteria,
    ): DeliverySlotSearchResult {
        $this->searchService->setSoapHeaderAccountNumber($this->accountNumber->getAccountNumber());
        $this->searchService->setSoapHeaderPassword($this->password->getPassword());

        $recipient = $criteria->getRecipientAddress();
        $shipper   = $criteria->getShipperAddress();

        $parameter = new SearchDeliverySlot(
            null,
            $criteria->getProductType()->value,
            $shipper?->getAddress1(),
            null,
            $shipper?->getPostCode()->getPostCode(),
            $shipper?->getCity(),
            $shipper?->getPostCode()->getCountryDelivery()->getCode(),
            $recipient->getAddress1(),
            null,
            $recipient->getPostCode()->getPostCode(),
            $recipient->getCity(),
            (string) $recipient->getPostCode()->getCountryDelivery()->getCode(),
            null,
            $criteria->getWeight() !== null ? (int) ($criteria->getWeight()->getKg() * 1000) : null,
            $criteria->getDateRange()->getBegin()->format('Y-m-d'),
            $criteria->getDateRange()->getEnd()->format('Y-m-d'),
        );

        if ($criteria->getSlotType() !== null) {
            $parameter->setSlotType($criteria->getSlotType()->value);
        }

        $result = $this->searchService->searchDeliverySlot($parameter);

        if ($result === false) {
            throw new ApiError(
                'Failed to call searchDeliverySlot',
                $this->searchService->getLastErrorForMethod(
                    Search::class . '::searchDeliverySlot',
                ),
            );
        }

        $response = $result->getReturn();

        if ($response === null) {
            throw new ApiError('Failed to get result from search service, null response');
        }

        DeliverySlotException::throwIfError(
            $response->getCode() ?? 0,
            $response->getMessage() ?? '',
        );

        $factory = new DeliverySlotSearchResultFactory();

        return $factory->create($response);
    }

    /**
     * Confirm a delivery time slot.
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot\DeliverySlotConfirmation
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\DeliverySlot\DeliverySlotException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     */
    public function confirmDeliverySlot(
        SlotConfirmRequest $request,
    ): DeliverySlotConfirmation {
        $this->confirmService->setSoapHeaderAccountNumber($this->accountNumber->getAccountNumber());
        $this->confirmService->setSoapHeaderPassword($this->password->getPassword());

        $parameter = new ConfirmDeliverySlotV2(
            null,
            $request->getProductType()->value,
            $request->getCodeSlot(),
            $request->getMeshCode(),
            $request->getTransactionId(),
            $request->getRank(),
            $request->getPosition(),
            $request->getDateSelected()->format('Y-m-d'),
        );

        $result = $this->confirmService->confirmDeliverySlotV2($parameter);

        if ($result === false) {
            throw new ApiError(
                'Failed to call confirmDeliverySlotV2',
                $this->confirmService->getLastErrorForMethod(
                    Confirm::class . '::confirmDeliverySlotV2',
                ),
            );
        }

        $response = $result->getReturn();

        if ($response === null) {
            throw new ApiError('Failed to get result from confirm service, null response');
        }

        DeliverySlotException::throwIfError(
            $response->getCode() ?? 0,
            $response->getMessage() ?? '',
        );

        $factory = new DeliverySlotConfirmationFactory();

        return $factory->create($response);
    }

    /**
     * Geocode an address to get coordinates.
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot\GeocodingResult
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\DeliverySlot\DeliverySlotException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     */
    public function geocodeAddress(
        GeocodingAddress $address,
    ): GeocodingResult {
        $this->getService->setSoapHeaderAccountNumber($this->accountNumber->getAccountNumber());
        $this->getService->setSoapHeaderPassword($this->password->getPassword());

        $parameter = new GetAdresseGeocodage(
            $address->getAddress1(),
            $address->getAddress2(),
            $address->getPostCode()->getPostCode(),
            $address->getCity(),
        );

        $result = $this->getService->getAdresseGeocodage($parameter);

        if ($result === false) {
            throw new ApiError(
                'Failed to call getAdresseGeocodage',
                $this->getService->getLastErrorForMethod(
                    Get::class . '::getAdresseGeocodage',
                ),
            );
        }

        $response = $result->getReturn();

        if ($response === null) {
            throw new ApiError('Failed to get result from geocoding service, null response');
        }

        DeliverySlotException::throwIfError(
            $response->getCode() ?? 0,
            $response->getMessage() ?? '',
        );

        $factory = new GeocodingResultFactory();

        return $factory->create($response);
    }
}
