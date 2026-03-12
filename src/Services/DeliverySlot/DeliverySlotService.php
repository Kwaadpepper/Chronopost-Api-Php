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
use Kwaadpepper\ChronopostApiPhp\ObjectValues\Password;
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
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber $accountNumber   The account number.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Password      $password        The password.
     * @param string                                                    $productType     The product type code.
     * @param string                                                    $recipientAddr1  Recipient address line 1.
     * @param string                                                    $recipientZip    Recipient postal code.
     * @param string                                                    $recipientCity   Recipient city.
     * @param string                                                    $recipientCountry Recipient country code.
     * @param string                                                    $dateBegin       Start date (YYYY-MM-DD).
     * @param string                                                    $dateEnd         End date (YYYY-MM-DD).
     * @param string|null                                               $shipperAddr1    Shipper address line 1.
     * @param string|null                                               $shipperZip      Shipper postal code.
     * @param string|null                                               $shipperCity     Shipper city.
     * @param string|null                                               $shipperCountry  Shipper country code.
     * @param integer|null                                              $weight          Weight in grams.
     * @param string|null                                               $slotType        Slot type filter.
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot\DeliverySlotSearchResult
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\DeliverySlot\DeliverySlotException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     */
    public function searchDeliverySlots(
        AccountNumber $accountNumber,
        Password $password,
        string $productType,
        string $recipientAddr1,
        string $recipientZip,
        string $recipientCity,
        string $recipientCountry,
        string $dateBegin,
        string $dateEnd,
        ?string $shipperAddr1 = null,
        ?string $shipperZip = null,
        ?string $shipperCity = null,
        ?string $shipperCountry = null,
        ?int $weight = null,
        ?string $slotType = null,
    ): DeliverySlotSearchResult {
        $this->searchService->setSoapHeaderAccountNumber($accountNumber->getAccountNumber());
        $this->searchService->setSoapHeaderPassword($password->getPassword());

        $parameter = new SearchDeliverySlot(
            null,
            $productType,
            $shipperAddr1,
            null,
            $shipperZip,
            $shipperCity,
            $shipperCountry,
            $recipientAddr1,
            null,
            $recipientZip,
            $recipientCity,
            $recipientCountry,
            null,
            $weight,
            $dateBegin,
            $dateEnd,
        );

        if ($slotType !== null) {
            $parameter->setSlotType($slotType);
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
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber $accountNumber The account number.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Password      $password      The password.
     * @param string                                                    $productType   The product type code.
     * @param string                                                    $codeSlot      The delivery slot code.
     * @param string                                                    $meshCode      The mesh code from search result.
     * @param string                                                    $transactionId The transaction ID from search result.
     * @param string                                                    $rank          The rank of the slot.
     * @param string                                                    $position      The position of the slot.
     * @param string                                                    $dateSelected  The selected date.
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot\DeliverySlotConfirmation
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\DeliverySlot\DeliverySlotException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     */
    public function confirmDeliverySlot(
        AccountNumber $accountNumber,
        Password $password,
        string $productType,
        string $codeSlot,
        string $meshCode,
        string $transactionId,
        string $rank,
        string $position,
        string $dateSelected,
    ): DeliverySlotConfirmation {
        $this->confirmService->setSoapHeaderAccountNumber($accountNumber->getAccountNumber());
        $this->confirmService->setSoapHeaderPassword($password->getPassword());

        $parameter = new ConfirmDeliverySlotV2(
            null,
            $productType,
            $codeSlot,
            $meshCode,
            $transactionId,
            $rank,
            $position,
            $dateSelected,
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
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\AccountNumber $accountNumber The account number.
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Password      $password      The password.
     * @param string                                                    $address1      Address line 1.
     * @param string                                                    $zipCode       Postal code.
     * @param string                                                    $city          City name.
     * @param string|null                                               $address2      Address line 2.
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\DeliverySlot\GeocodingResult
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\DeliverySlot\DeliverySlotException
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\ApiError
     */
    public function geocodeAddress(
        AccountNumber $accountNumber,
        Password $password,
        string $address1,
        string $zipCode,
        string $city,
        ?string $address2 = null,
    ): GeocodingResult {
        $this->getService->setSoapHeaderAccountNumber($accountNumber->getAccountNumber());
        $this->getService->setSoapHeaderPassword($password->getPassword());

        $parameter = new GetAdresseGeocodage(
            $address1,
            $address2,
            $zipCode,
            $city,
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
