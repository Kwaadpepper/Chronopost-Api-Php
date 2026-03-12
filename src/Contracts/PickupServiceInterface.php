<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Contracts;

use ChronopostShipping\StructType\AdresseEnlevementV3;
use ChronopostShipping\StructType\DestinatairesDpd;
use ChronopostShipping\StructType\DonneurDOrdre;
use ChronopostShipping\StructType\HeaderValue;
use ChronopostShipping\StructType\Options;
use ChronopostShipping\StructType\ParticularitesEsd;
use ChronopostShipping\StructType\ShipperValue;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\CancelPickupResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\PickupConstraints;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\PickupCreationResult;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\PickupFeasibility;

interface PickupServiceInterface
{
    public function checkFeasibility(
        ShipperValue $shipperValue,
        string $retrievalDateTime,
        string $closingDateTime,
    ): PickupFeasibility;

    public function searchConstraints(
        string $country,
        string $zipCode,
        string $city,
    ): PickupConstraints;

    public function createNationalPickup(
        HeaderValue $headerValue,
        string $datePassage,
        string $datePassageFermeture,
        DonneurDOrdre $donneurDOrdre,
        AdresseEnlevementV3 $adresseEnlevement,
        ?ParticularitesEsd $particularitesEsd = null,
        ?string $referenceEsdClient = null,
        ?string $contenu = null,
        ?Options $options = null,
        ?string $locale = null,
    ): PickupCreationResult;

    public function createEuropeanPickup(
        HeaderValue $headerValue,
        string $datePassage,
        DonneurDOrdre $donneurDOrdre,
        AdresseEnlevementV3 $adresseEnlevement,
        ?DestinatairesDpd $destinatairesEsd = null,
        ?string $locale = null,
    ): PickupCreationResult;

    /**
     * @param string[] $esdNumbers
     */
    public function cancelPickups(
        array $esdNumbers,
        ?string $locale = null,
    ): CancelPickupResult;
}
