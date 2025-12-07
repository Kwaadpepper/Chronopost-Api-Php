<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Enums;

enum SkyBillOutputMode: int
{
    /**
     *  la LT est envoyée à l'adresse mail
     *  shipperEmail. Si la structure
     *  esdValue est renseignée, ce mail
     *  servira à confirmer l'enlèvement et à
     *  envoyer les LT
     */
    case SHIPPER_MAIL_SENDING = 1;

    /**
     * pas d'envoi de mail
     */
    case NO_MAIL_SENDING = 2;

    /**
     * la LT peut être imprimée en
     * Bureau de Poste, et envoi de SMS
     */
    case POST_OFFICE_AND_SMS_PRINTABLE = 3;

    /**
     * la LT est envoyée à l'adresse
     * mail shipperEmail et au
     * destinataire final (shop2shop)
     */
    case SHOP2SHOP_EMAIL = 4;
}
