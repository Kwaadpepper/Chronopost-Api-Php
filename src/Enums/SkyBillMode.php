<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Enums;

/**
 * PDF|XML :        combinaison à utiliser pour une
 *                  impression sur un automate en Bureau de
 *                  Poste
 *
 *
 * PPR|SLT|XML :    combinaison à utiliser
 *                  pour un envoi Shop 2 Shop, national ou
 *                  europe
 *
 * PDF|SLT|XML :    combinaison à utiliser
 *                  pour un envoi Shop 2 Shop, national ou
 *                  europe
 *
 *  Avec le service
 *  shippingWithReservationAndESDWithRef
 *  Client, il est possible de produire plusieurs
 *  étiquettes en une seule requête, PDF|Z2D
 *  produira par exemple 2 LT (une en PDF et
 *  l’autre en Z2D)
 *  En THE, le flux renvoyé reste un flux PDF
 *  qui est mis en page différemment pour
 *  correspondre au format A6 des étiquettes
 *  thermiques. Il faut que ces imprimantes
 *  acceptent l’impression via le driver
 *  bureautiqu
 */
enum SkyBillMode: string
{
    /**
     * LT avec preuve de dépôt destinée à être imprimée sur une
     * imprimante papier, format A
     */
    case PDF = 'PDF';

    /**
     * LT au format PDF, la partie preuve de dépôt contient les 5 Points
     * Relais les plus proches de l'expédition
     */
    case PPR = 'PPR';

    /**
     *  LT au format PDF sans preuve de dépôt et destinée à être imprimée
     * sur une imprimante thermique compatible ZPL, format A4
     */
    case The = 'THE';

    /**
     * LT au format PDF destinée à être imprimée sur une imprimante
     * thermique compatible ZPL, format 10x15
     */
    case THE1015 = 'THE1015';

    /**
     * LT au format ZPLdestinée à être imprimée sur une imprimante thermique
     */
    case Z2D = 'Z2D';

    case JSON = 'JSON';

    /**
     * Mode utilisé pour le E-Label envoi d’un SMS
     */
    case SLT = 'SLT';

    /**
     * Flux xml permettant de créer une LT sans code à barre 2D
     */
    case XML = 'XML';
    /**
     * Flux xml permettant de créer une LT avec code à barre 2D
     */
    case XML2D = 'XML2D';

    /**
     * Identique au format THE mais en orientation paysage
     */
    case THEPSG = 'THEPSG';

    /**
     * LT au format ZPL et destinée à être imprimée sur une imprimante thermique 300dp
     */
    case ZPL300 = 'ZPL300';
}
