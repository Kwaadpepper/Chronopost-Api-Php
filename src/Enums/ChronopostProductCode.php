<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Enums;

/**
 * Enumération des codes de produits Chronopost pour l'intégration API.
 *
 * Basé sur la documentation officielle et les standards techniques Chronopost.
 *
 * INFORMATIONS GÉNÉRALES (sauf mention spécifique) :
 * - Poids max standard : 30 kg
 * - Dimensions max standard : L <= 150 cm | L + 2H + 2l <= 300 cm
 * - Dimensions min : 30 x 21 cm
 */
enum ChronopostProductCode: string
{
    // --- OFFRES DOMESTIQUES STANDARD ---

    /**
     * Chrono 13
     * Livraison le lendemain avant 13h.
     * Max: 30kg.
     */
    case CHRONO_13 = '01';

    /**
     * Chrono 13 Web
     * Variante Web du Chrono 13.
     */
    case CHRONO_13_WEB = '5A';

    /**
     * Chrono 10
     * Livraison le lendemain avant 10h.
     * Max: 30kg.
     */
    case CHRONO_10 = '02';

    /**
     * Chrono 18
     * Livraison le lendemain avant 18h.
     * Max: 30kg.
     */
    case CHRONO_18 = '16';

    /**
     * Chrono 8
     * Livraison le lendemain avant 8h.
     * Max: 30kg.
     */
    case CHRONO_8 = '75';

    /**
     * Chrono 9
     * Livraison le lendemain avant 9h.
     * Max: 30kg.
     */
    case CHRONO_9 = '76';

    /**
     * Chrono 12
     * Livraison le lendemain avant 12h.
     * Max: 30kg.
     */
    case CHRONO_12 = '77';

    /**
     * Chrono Retrait Bureau
     * Code '0' dans la doc, mappé souvent en interne.
     */
    case CHRONO_RETRAIT_BUREAU = '00';

    /**
     * Chrono REP (Réponse)
     */
    case CHRONO_REP = '09';

    // --- OFFRES RELAIS (PICKUP) ---

    /**
     * Chrono Relais 13
     * Livraison en relais le lendemain avant 13h.
     * Max: 20kg | L <= 100cm | L+2H+2l <= 250cm
     * Note: La doc indique parfois '86' comme Europe par erreur, c'est bien le standard domestique.
     */
    case CHRONO_RELAIS_13 = '86';

    /**
     * Chrono 9 Relais
     * Livraison en relais avant 9h.
     */
    case CHRONO_9_RELAIS = '80';

    /**
     * Chrono Relais DOM
     */
    case CHRONO_RELAIS_DOM = '4P';

    // --- OFFRES INTERNATIONALES ---

    /**
     * Chrono Express International
     * Livraison express monde.
     * Max: 30kg.
     */
    case CHRONO_EXPRESS = '17';

    /**
     * Chrono Premium International
     */
    case CHRONO_PREMIUM = '37';

    /**
     * Chrono Classic
     * Livraison routière Europe.
     * Max: 30kg.
     */
    case CHRONO_CLASSIC = '44';

    /**
     * Chrono Relais Europe
     * Max: 20kg.
     */
    case CHRONO_RELAIS_EUROPE = '49';

    /**
     * Chrono Retour Europe
     */
    case CHRONO_RETOUR_EUROPE = '3T';

    // --- OFFRES SPÉCIFIQUES ---

    /**
     * Chrono Agenda / Precise
     * Livraison sur RDV créneau 2h (J+1 à J+14).
     */
    case CHRONO_AGENDA = '20';

    /**
     * Chrono Rendez-Vous
     * Même famille que Precise/Agenda.
     */
    case CHRONO_RENDEZ_VOUS = '2O'; // Lettre O

    /**
     * Chrono Same Day
     * Livraison le jour même (19h-22h).
     */
    case CHRONO_SAME_DAY = '4I'; // Doc indique 4I. Ancien code API parfois 5J.

    /**
     * Chrono Direct
     */
    case CHRONO_DIRECT = '4Q';

    // --- LOGISTIQUE INVERSE (RETOURS) ---

    case CHRONO_REVERSE_9 = '4R';
    case CHRONO_REVERSE_10 = '4S';
    case CHRONO_REVERSE_13 = '4T';
    case CHRONO_REVERSE_18 = '4U';

    // --- CHRONO FRESH (ALIMENTAIRE SEC/FRAIS) ---
    // Max: 30kg | Dimensions spécifiques max : L85 x l45 x h50 cm pour Fresh/Freeze

    case CHRONO_TEMP_13 = '78';
    case CHRONO_TEMP_10 = '1K';

    case CHRONO_FRESH_13 = '2R';
    case CHRONO_FRESH_10 = '3X';
    case CHRONO_FRESH_12 = '4V';
    case CHRONO_FRESH_CLASSIC = '4X';
    case CHRONO_FRESH_RENDEZ_VOUS = '2E';
    case CHRONO_FRESH_SAME_DAY = '2P';

    // --- CHRONO FREEZE (SURGELÉ) ---

    case CHRONO_FREEZE_13 = '2S';
    case CHRONO_FREEZE_10 = '3Y';
    case CHRONO_FREEZE_12 = '4W';
    case CHRONO_FREEZE_RENDEZ_VOUS = '2F';
    case CHRONO_FREEZE_SAME_DAY = '2Q';

    // --- CHRONO MEDICAL ---

    case CHRONO_MEDICAL_8 = '2A';
    case CHRONO_MEDICAL_9 = '2B';
    case CHRONO_MEDICAL_10 = '8A';
    case CHRONO_MEDICAL_13 = '8B';
    case CHRONO_MEDICAL_18 = '8C';
    case CHRONO_MEDICAL_10_THERMOSENSIBLE = '8D';
    case CHRONO_MEDICAL_13_THERMOSENSIBLE = '8E';
    case CHRONO_MEDICAL_18_THERMOSENSIBLE = '8F';
    case CHRONO_MEDICAL_MD_13 = '8G'; // MD = Marchandises Dangereuses
    case CHRONO_MEDICAL_MD_18 = '8H';
    case CHRONO_MEDICAL_MD_13_THERMO = '8I';
    case CHRONO_MEDICAL_MD_18_THERMO = '8J';

    // --- MARCHANDISES DANGEREUSES (HORS MEDICAL) ---

    case CHRONO_MD_13 = '1M';
    case CHRONO_MD_18 = '1N';

    // --- INSTANCES & OPTIONS SPÉCIFIQUES ---

    case CHRONO_13_BAL_INSTANCE_RELAIS_BP = '58'; // Chrono 13 BAL
    case CHRONO_13_INSTANCE_POSTE = '93';
    case CHRONO_13_REMISE_PAS_DE_PORTE = '1F';
    case CHRONO_13_REMISE_PAS_DE_PORTE_ALT = '1G'; // Doc indique 13, souvent lié au 18 dans les patterns, mais respect doc.
    case CHRONO_13_INSTANCE_AGENCE = '1S';
    case CHRONO_13_INSTANCE_RELAIS = '1T';
    case CHRONO_13_LIVRAISON_COLLECTE = '1Y';

    case CHRONO_18_BAL = '2L';
    case CHRONO_18_BAL_INSTANCE_MIXTE = '2M';
    case CHRONO_18_INSTANCE_AGENCE = '1V';
    case CHRONO_18_INSTANCE_RELAIS = '3Z';

    case CHRONO_10_SANS_INSTANCE_POSTE = '1U';

    // --- SWAP (ÉCHANGE) ---

    case CHRONO_SWAP_13 = '1O'; // Lettre O
    case CHRONO_SWAP_18 = '1P';

    // --- ZENGO (URBAIN ÉCOLOGIQUE) ---

    case CHRONO_ZENGO_13 = '3J';
    case CHRONO_ZENGO_RELAIS_13 = '3K';

    // --- FRET & DOM ---
    // Fret: > 30kg.

    case CHRONO_FRET_DOM = '3S';

    /**
     * Valeur par défaut pour les codes inconnus.
     */
    case UNKNOWN = 'XX';

    /**
     * Retourne le nom lisible du produit Chronopost.
     */
    public function getProductName(): string
    {
        return match ($this) {
            self::CHRONO_13 => 'Chrono 13',
            self::CHRONO_13_WEB => 'Chrono 13 Web',
            self::CHRONO_10 => 'Chrono 10',
            self::CHRONO_18 => 'Chrono 18',
            self::CHRONO_8 => 'Chrono 8',
            self::CHRONO_9 => 'Chrono 9',
            self::CHRONO_12 => 'Chrono 12',
            self::CHRONO_RETRAIT_BUREAU => 'Chrono Retrait Bureau',
            self::CHRONO_REP => 'Chrono REP',
            self::CHRONO_RELAIS_13 => 'Chrono Relais 13',
            self::CHRONO_9_RELAIS => 'Chrono 9 Relais',
            self::CHRONO_RELAIS_DOM => 'Chrono Relais DOM',
            self::CHRONO_EXPRESS => 'Chrono Express International',
            self::CHRONO_PREMIUM => 'Chrono Premium',
            self::CHRONO_CLASSIC => 'Chrono Classic',
            self::CHRONO_RELAIS_EUROPE => 'Chrono Relais Europe',
            self::CHRONO_RETOUR_EUROPE => 'Chrono Retour Europe',
            self::CHRONO_AGENDA => 'Chrono Agenda',
            self::CHRONO_RENDEZ_VOUS => 'Chrono Rendez-Vous',
            self::CHRONO_SAME_DAY => 'Chrono Same Day',
            self::CHRONO_DIRECT => 'Chrono Direct',
            self::CHRONO_REVERSE_9 => 'Chrono Reverse 9',
            self::CHRONO_REVERSE_10 => 'Chrono Reverse 10',
            self::CHRONO_REVERSE_13 => 'Chrono Reverse 13',
            self::CHRONO_REVERSE_18 => 'Chrono Reverse 18',
            self::CHRONO_TEMP_13 => 'Chrono Temp° 13',
            self::CHRONO_TEMP_10 => 'Chrono Temp° 10',
            self::CHRONO_FRESH_13 => 'Chrono Fresh 13',
            self::CHRONO_FRESH_10 => 'Chrono Fresh 10',
            self::CHRONO_FRESH_12 => 'Chrono Fresh 12',
            self::CHRONO_FRESH_CLASSIC => 'Chrono Fresh Classic',
            self::CHRONO_FRESH_RENDEZ_VOUS => 'Chrono Fresh Rendez-Vous',
            self::CHRONO_FRESH_SAME_DAY => 'Chrono Fresh Same Day',
            self::CHRONO_FREEZE_13 => 'Chrono Freeze 13',
            self::CHRONO_FREEZE_10 => 'Chrono Freeze 10',
            self::CHRONO_FREEZE_12 => 'Chrono Freeze 12',
            self::CHRONO_FREEZE_RENDEZ_VOUS => 'Chrono Freeze Rendez-Vous',
            self::CHRONO_FREEZE_SAME_DAY => 'Chrono Freeze Same Day',
            self::CHRONO_MEDICAL_8 => 'Chrono Medical 8',
            self::CHRONO_MEDICAL_9 => 'Chrono Medical 9',
            self::CHRONO_MEDICAL_10 => 'Chrono Medical 10',
            self::CHRONO_MEDICAL_13 => 'Chrono Medical 13',
            self::CHRONO_MEDICAL_18 => 'Chrono Medical 18',
            self::CHRONO_MEDICAL_10_THERMOSENSIBLE => 'Chrono Medical 10 Thermosensible',
            self::CHRONO_MEDICAL_13_THERMOSENSIBLE => 'Chrono Medical 13 Thermosensible',
            self::CHRONO_MEDICAL_18_THERMOSENSIBLE => 'Chrono Medical 18 Thermosensible',
            self::CHRONO_MEDICAL_MD_13 => 'Chrono Medical MD 13',
            self::CHRONO_MEDICAL_MD_18 => 'Chrono Medical MD 18',
            self::CHRONO_MEDICAL_MD_13_THERMO => 'Chrono Medical MD 13 Thermosensible',
            self::CHRONO_MEDICAL_MD_18_THERMO => 'Chrono Medical MD 18 Thermosensible',
            self::CHRONO_MD_13 => 'Chrono Marchandises Dangereuses 13',
            self::CHRONO_MD_18 => 'Chrono Marchandises Dangereuses 18',
            self::CHRONO_13_BAL_INSTANCE_RELAIS_BP => 'Chrono 13 BAL (instance relais et Poste)',
            self::CHRONO_13_INSTANCE_POSTE => 'Chrono 13 Instance Poste',
            self::CHRONO_13_REMISE_PAS_DE_PORTE => 'Chrono 13 (remise pas de porte possible)',
            self::CHRONO_13_REMISE_PAS_DE_PORTE_ALT => 'Chrono 13 (remise pas de porte possible) [Alt]',
            self::CHRONO_13_INSTANCE_AGENCE => 'Chrono 13 Instance Agence',
            self::CHRONO_13_INSTANCE_RELAIS => 'Chrono 13 Instance Relais',
            self::CHRONO_13_LIVRAISON_COLLECTE => 'Chrono 13 Livraison/Collecte',
            self::CHRONO_18_BAL => 'Chrono 18 (livraison boîte aux lettres)',
            self::CHRONO_18_BAL_INSTANCE_MIXTE => 'Chrono 18 BAL (instance mixte)',
            self::CHRONO_18_INSTANCE_AGENCE => 'Chrono 18 Instance Agence',
            self::CHRONO_18_INSTANCE_RELAIS => 'Chrono 18 Instance Relais',
            self::CHRONO_10_SANS_INSTANCE_POSTE => 'Chrono 10 (sans instance Poste)',
            self::CHRONO_SWAP_13 => 'Chrono Swap 13',
            self::CHRONO_SWAP_18 => 'Chrono Swap 18',
            self::CHRONO_ZENGO_13 => 'Chrono Zengo 13',
            self::CHRONO_ZENGO_RELAIS_13 => 'Chrono Zengo Relais 13',
            self::CHRONO_FRET_DOM => 'Chrono Fret DOM',
            self::UNKNOWN => 'Produit Inconnu',
        };
    }

    /**
     * Helper pour éviter les exceptions sur codes inconnus.
     */
    public static function tryFromOrUnknown(?string $code): self
    {
        if ($code === null) {
            return self::UNKNOWN;
        }
        return self::tryFrom(str_pad($code, 2, '0', STR_PAD_LEFT)) ?? self::UNKNOWN;
    }
}
