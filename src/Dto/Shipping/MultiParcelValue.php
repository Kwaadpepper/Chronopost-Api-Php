<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\Shipping;

use Kwaadpepper\ChronopostApiPhp\Dto\Dto;

/**
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */
readonly class MultiParcelValue implements Dto
{
    /**
     * Valeurs des produits de livraison pour un envoi multi-colis.
     *
     * @param string|null                                                $asCode                Code lié au produit de livraison.
     * @param string                                                     $codeDepot             Code du dépôt.
     * @param string                                                     $codeService           Code du service lié au produit de livraison.
     * @param string                                                     $destinationDepot      Code de l'agence Chronopost de livraison.
     * @param string                                                     $geoPostCodeBarre      Code à barre de routage.
     * @param string                                                     $geoPostNumeroColis    Numéro de colis DPD Group.
     * @param string                                                     $groupingPriorityLabel Code de l'agence Chronopost qui gère la livraison.
     * @param \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\TransportTicket $transportTicket       Ticket de transport associé à l'envoi.
     * @param string                                                     $serviceMark           Marquage du colis.
     * @param string                                                     $serviceName           Libellé du service DPD Group de livraison souhaité.
     * @param string                                                     $signaletiqueProduit   Libellé du service de livraison souhaité.
     * @param string                                                     $skybillNumber         Numéro de la lettre de transport.
     * @param string|null                                                $DSort                 Distri Sort, code de tournée de livraison.
     * @param string|null                                                $OSort                 Origin Sort.
     */
    public function __construct(
        public string|null $asCode,
        public string $codeDepot,
        public string $codeService,
        public string $destinationDepot,
        public string $geoPostCodeBarre,
        public string $geoPostNumeroColis,
        public string $groupingPriorityLabel,
        public TransportTicket $transportTicket,
        public string $serviceMark,
        public string $serviceName,
        public string $signaletiqueProduit,
        public string $skybillNumber,
        public string|null $DSort,
        public string|null $OSort,
    ) {
    }
}
