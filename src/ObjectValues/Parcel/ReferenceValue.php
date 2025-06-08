<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel;

use Kwaadpepper\ChronopostApiPhp\Helpers\StringHelper;

readonly class ReferenceValue
{
    /**
     * ReferenceValue
     *
     * @param string|null $customerSkyBillNumber Numéro de colis client, tronqué  à 15 caractère.
     * @param string|null $recipientReference    Référence  du destinatair, 35 alphanumeric characters max.
     * @param string|null $shipperReference      Référence de l'expéditeur, 35 alphanumeric characters max.
     * @param string|null $relayIdentifier       Identiffiant du Point Relais, 4 digits followed by a letter.
     *
     * @throws \InvalidArgumentException If the provided argument is invalid.
     */
    public function __construct(
        public string|null $customerSkyBillNumber = null,
        public string|null $recipientReference = null,
        public string|null $shipperReference = null,
        public string|null $relayIdentifier = null,
    ) {
        StringHelper::validateValue($customerSkyBillNumber, 'customerSkyBillNumber', '/^[a-zA-Z0-9]{0,15}$/');
        StringHelper::validateValue($recipientReference, 'recipientReference', '/^[a-zA-Z0-9]{0,35}$/');
        StringHelper::validateValue($shipperReference, 'shipperReference', '/^[a-zA-Z0-9]{0,35}$/');
        StringHelper::validateValue($relayIdentifier, 'relayIdentifier', '/^[0-9]{4}[a-zA-Z]{1}$/');
    }
}
