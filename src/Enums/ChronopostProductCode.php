<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Enums;

/**
 * Enumération des codes de produits Chronopost pour l'intégration API (Shipping V4).
 *
 * Basé sur la documentation officielle et les standards techniques Chronopost (PrestaShop/API).
 *
 * @see https://sc.webetsolutions.com/support/solutions/articles/26000039872-chronopost-les-diff%C3%A9rents-services-disponibles
 * @see https://docs.karrio.io/carriers/integrations/chronopost
 * @see https://www.chronopost.fr/fr/professionnel/restrictions-offres
 */
enum ChronopostProductCode: string
{
    // --- NATIONAL ---
    case CHRONO_13        = '01';
    case CHRONO_10        = '02';
    case CHRONO_18        = '16';
    case CHRONO_SAMEDI    = '05';

    case CHRONO_RELAIS_13         = '5A';
    case CHRONO_RELAIS_13_SPECIAL = '5L'; // Variante contractuelle ou Marketplace

    // --- INTERNATIONAL ---
    case CHRONO_CLASSIC   = '06'; // Souvent Export
    case CHRONO_INTL_EXP  = '07'; // Export Express

    // --- RETRAIT ---
    case CHRONO_RETRAIT_BUREAU = '00';

    // --- CODES PETITS PROS ---
    case CHRONO_10_PETITPROS           = '9B';
    case CHRONO_13_PETITPROS           = '9A';
    case CHRONO_18_PETITPROS           = '9C';
    case CHRONO_CLASSIC_PETITPROS      = '9F';
    case CHRONO_EXPRESS_PETITPROS      = '9D';
    case CHRONO_TO_SHOP_DIRECT_PETITPROS = '5E';

    // --- INTERNATIONAL SPÉCIFIQUE ---
    case CHRONO_EXPRESS_INTERNATIONAL = '17';
    case CHRONO_PREMIUM_INTERNATIONAL = '37';
    case CHRONO_CLASSIC_INTERNATIONAL = '44';

    // --- SERVICES SPÉCIFIQUES & OPTIONS ---
    case CHRONO_REP                                                = '09'; // Reverse
    case CHRONO_AGENDA                                             = '20'; // Sur RDV
    case CHRONO_RELAIS_EUROPE                                      = '49'; // Relais Europe
    case CHRONO_RELAIS                                             = '86'; // Relais national
    case CHRONO_RELAIS_AMBIENT                                     = '5Q'; // Relais Ambient
    case CHRONO_TO_SHOP_DIRECT                                     = '5X'; // ToShopDirect national
    case CHRONO_TO_SHOP_DIRECT_EUROPE                              = '6B'; // ToShopDirect Europe

    // --- B2B & EXPRESS MATIN ---
    case CHRONO_8                                                  = '75';
    case CHRONO_9                                                  = '76';
    case CHRONO_12                                                 = '77';
    case CHRONO_TEMP_13                                            = '78'; // Temporaire/Spécifique
    case CHRONO_9_RELAIS                                           = '80';

    // --- SPÉCIFIQUE LIVRAISON ---
    case CHRONO_13_BAL                                             = '58'; // Boite aux lettres
    case CHRONO_13_POSTE                                           = '93';
    case CHRONO_13_REMISE_PAS_DE_PORTE                             = '1F';
    case CHRONO_13_REMISE_PAS_DE_PORTE_2                           = '1G';
    case CHRONO_TEMP_10                                            = '1K';

    // --- MARCHANDISES DANGEREUSES (ADR) ---
    case CHRONO_MARCHANDISES_DANGEREUSES_13                        = '1M';
    case CHRONO_MARCHANDISES_DANGEREUSES_18                        = '1N';

    // --- SWAP (ECHANGE) ---
    case CHRONO_SWAP_13                                            = '1O';
    case CHRONO_SWAP_18                                            = '1P';

    // --- GESTION INSTANCE ---
    case CHRONO_13_INSTANCE_AGENCE                                 = '1S';
    case CHRONO_13_INSTANCE_RELAIS                                 = '1T';
    case CHRONO_10_SANS_INSTANCE_POSTE                             = '1U';
    case CHRONO_18_INSTANCE_AGENCE                                 = '1V';
    case CHRONO_13_LIVRAISON_COLLECTE                              = '1Y';
    case CHRONO_18_INSTANCE_RELAIS                                 = '3Z';

    // --- MEDICAL / SANTÉ ---
    case CHRONO_8_MEDICAL                                          = '2A';
    case CHRONO_9_MEDICAL                                          = '2B';
    case CHRONO_MEDICAL_10                                         = '8A';
    case CHRONO_MEDICAL_13                                         = '8B';
    case CHRONO_MEDICAL_18                                         = '8C';
    case CHRONO_MEDICAL_10_THERMOSENSIBLE                          = '8D';
    case CHRONO_MEDICAL_13_THERMOSENSIBLE                          = '8E';
    case CHRONO_MEDICAL_18_THERMOSENSIBLE                          = '8F';
    case CHRONO_MEDICAL_MARCHANDISES_DANGEREUSES_13                = '8G';
    case CHRONO_MEDICAL_MARCHANDISES_DANGEREUSES_18                = '8H';
    case CHRONO_MEDICAL_MARCHANDISES_DANGEREUSES_13_THERMOSENSIBLE = '8I';
    case CHRONO_MEDICAL_MARCHANDISES_DANGEREUSES_18_THERMOSENSIBLE = '8J';

    // --- FOOD (FRESH/FREEZE) ---
    case CHRONO_FRESH_RENDEZ_VOUS                                  = '2E';
    case CHRONO_FREEZE_RENDEZ_VOUS                                 = '2F';
    case CHRONO_FRESH_SAME_DAY                                     = '2P';
    case CHRONO_FREEZE_SAME_DAY                                    = '2Q';
    case CHRONO_FRESH_13                                           = '2R';
    case CHRONO_FREEZE_13                                          = '2S';
    case CHRONO_FRESH_10                                           = '3X';
    case CHRONO_FREEZE_10                                          = '3Y';
    case CHRONO_FRESH_12                                           = '4V';
    case CHRONO_FREEZE_12                                          = '4W';
    case CHRONO_FRESH_CLASSIC                                      = '4X';

    // --- AUTRES OPTIONS ---
    case CHRONO_18_LIVRAISON_BOITE_A_LETTRES                       = '2L';
    case CHRONO_18_LIVRAISON_BOITE_A_LETTRES_MIXTE                 = '2M';
    case CHRONO_RENDEZ_VOUS                                        = '2O'; // Souvent appelé Chrono Precise
    case CHRONO_ZENGO_13                                           = '3J';
    case CHRONO_ZENGO_RELAIS_13                                    = '3K';
    case CHRONO_FRET_DOM                                           = '3S';
    case CHRONO_RETOUR_EUROPE                                      = '3T';
    case CHRONO_SAME_DAY                                           = '4I';
    case CHRONO_RELAIS_DOM                                         = '4P';
    case CHRONO_DIRECT                                             = '4Q'; // Livraison directe/Course

    // --- REVERSE (RETOUR) ---
    case CHRONO_REVERSE_9                                          = '4R';
    case CHRONO_REVERSE_10                                         = '4S';
    case CHRONO_REVERSE_13                                         = '4T';
    case CHRONO_REVERSE_18                                         = '4U';

    // --- INCONNU ---
    case UNKNOWN = 'XX';

    /**
     * Récupère le libellé commercial en français
     */
    public function getLabel(): string
    {
        return match ($this) {
            // National
            self::CHRONO_13                 => 'Chrono 13',
            self::CHRONO_10                 => 'Chrono 10',
            self::CHRONO_18                 => 'Chrono 18',
            self::CHRONO_SAMEDI             => 'Chrono 13 Samedi',
            self::CHRONO_RELAIS_13          => 'Chrono Relais 13',
            self::CHRONO_RELAIS_13_SPECIAL  => 'Chrono Relais 13 Spécial',

            // International
            self::CHRONO_CLASSIC               => 'Chrono Classic',
            self::CHRONO_INTL_EXP              => 'Chrono Express International',
            self::CHRONO_EXPRESS_INTERNATIONAL => 'Chrono Express International',
            self::CHRONO_PREMIUM_INTERNATIONAL => 'Chrono Premium International',
            self::CHRONO_CLASSIC_INTERNATIONAL => 'Chrono Classic International',
            self::CHRONO_RELAIS_EUROPE         => 'Chrono Relais Europe',
            self::CHRONO_RELAIS                => 'Chrono Relais',
            self::CHRONO_RELAIS_AMBIENT        => 'Chrono Relais Ambient',
            self::CHRONO_TO_SHOP_DIRECT        => 'Chrono ToShop Direct',
            self::CHRONO_TO_SHOP_DIRECT_EUROPE => 'Chrono ToShop Direct Europe',
            self::CHRONO_RETOUR_EUROPE         => 'Chrono Retour Europe',

            // Retrait / Point
            self::CHRONO_RETRAIT_BUREAU             => 'Retrait en Bureau de Poste',
            self::CHRONO_TO_SHOP_DIRECT_PETITPROS   => 'Chrono ToShop Direct Petit Pros',
            self::CHRONO_RELAIS_DOM                 => 'Chrono Relais DOM',
            self::CHRONO_9_RELAIS                   => 'Chrono 9 Relais',

            // Anciens codes / Compat
            self::CHRONO_10_PETITPROS      => 'Chrono 10 Petit Pros',
            self::CHRONO_13_PETITPROS      => 'Chrono 13 Petit Pros',
            self::CHRONO_18_PETITPROS      => 'Chrono 18 Petit Pros',
            self::CHRONO_CLASSIC_PETITPROS => 'Chrono Classic Petit Pros',
            self::CHRONO_EXPRESS_PETITPROS => 'Chrono Express Petit Pros',

            // Services Spéciaux / Horaire
            self::CHRONO_REP         => 'Chrono Reverse (REP)',
            self::CHRONO_AGENDA      => 'Chrono Agenda (Sur RDV)',
            self::CHRONO_8           => 'Chrono 8',
            self::CHRONO_9           => 'Chrono 9',
            self::CHRONO_12          => 'Chrono 12',
            self::CHRONO_RENDEZ_VOUS => 'Chrono Rendez-vous',
            self::CHRONO_SAME_DAY    => 'Chrono Same Day',
            self::CHRONO_DIRECT      => 'Chrono Direct',

            // Spécificités Livraison 13/18
            self::CHRONO_13_BAL                             => 'Chrono 13 BAL',
            self::CHRONO_13_POSTE                           => 'Chrono 13 Poste',
            self::CHRONO_13_REMISE_PAS_DE_PORTE             => 'Chrono 13 (Pas de porte)',
            self::CHRONO_13_REMISE_PAS_DE_PORTE_2           => 'Chrono 13 (Pas de porte)',
            self::CHRONO_13_LIVRAISON_COLLECTE              => 'Chrono 13 Livraison Collecte',
            self::CHRONO_18_LIVRAISON_BOITE_A_LETTRES       => 'Chrono 18 BAL',
            self::CHRONO_18_LIVRAISON_BOITE_A_LETTRES_MIXTE => 'Chrono 18 BAL Mixte',

            // Marchandises Dangereuses
            self::CHRONO_MARCHANDISES_DANGEREUSES_13 => 'Chrono 13 (ADR)',
            self::CHRONO_MARCHANDISES_DANGEREUSES_18 => 'Chrono 18 (ADR)',

            // Reverse
            self::CHRONO_REVERSE_9  => 'Chrono Reverse 9',
            self::CHRONO_REVERSE_10 => 'Chrono Reverse 10',
            self::CHRONO_REVERSE_13 => 'Chrono Reverse 13',
            self::CHRONO_REVERSE_18 => 'Chrono Reverse 18',

            // Swap
            self::CHRONO_SWAP_13 => 'Chrono Swap 13',
            self::CHRONO_SWAP_18 => 'Chrono Swap 18',

            // Fresh & Freeze (Alimentaire)
            self::CHRONO_FRESH_13          => 'Chrono Fresh 13',
            self::CHRONO_FRESH_10          => 'Chrono Fresh 10',
            self::CHRONO_FRESH_12          => 'Chrono Fresh 12',
            self::CHRONO_FRESH_SAME_DAY    => 'Chrono Fresh SameDay',
            self::CHRONO_FRESH_RENDEZ_VOUS => 'Chrono Fresh RDV',
            self::CHRONO_FRESH_CLASSIC     => 'Chrono Fresh Classic',

            self::CHRONO_FREEZE_13          => 'Chrono Freeze 13',
            self::CHRONO_FREEZE_10          => 'Chrono Freeze 10',
            self::CHRONO_FREEZE_12          => 'Chrono Freeze 12',
            self::CHRONO_FREEZE_SAME_DAY    => 'Chrono Freeze SameDay',
            self::CHRONO_FREEZE_RENDEZ_VOUS => 'Chrono Freeze RDV',

            // Santé / Médical
            self::CHRONO_8_MEDICAL                                          => 'Chrono 8 Medical',
            self::CHRONO_9_MEDICAL                                          => 'Chrono 9 Medical',
            self::CHRONO_MEDICAL_10                                         => 'Chrono Medical 10',
            self::CHRONO_MEDICAL_13                                         => 'Chrono Medical 13',
            self::CHRONO_MEDICAL_18                                         => 'Chrono Medical 18',
            self::CHRONO_MEDICAL_10_THERMOSENSIBLE                          => 'Chrono Medical 10 Thermo',
            self::CHRONO_MEDICAL_13_THERMOSENSIBLE                          => 'Chrono Medical 13 Thermo',
            self::CHRONO_MEDICAL_18_THERMOSENSIBLE                          => 'Chrono Medical 18 Thermo',
            self::CHRONO_MEDICAL_MARCHANDISES_DANGEREUSES_13                => 'Chrono Medical 13 ADR',
            self::CHRONO_MEDICAL_MARCHANDISES_DANGEREUSES_18                => 'Chrono Medical 18 ADR',
            self::CHRONO_MEDICAL_MARCHANDISES_DANGEREUSES_13_THERMOSENSIBLE => 'Chrono Medical 13 ADR Thermo',
            self::CHRONO_MEDICAL_MARCHANDISES_DANGEREUSES_18_THERMOSENSIBLE => 'Chrono Medical 18 ADR Thermo',

            // Autres / Tech
            self::CHRONO_TEMP_13         => 'Chrono 13 (Temp)',
            self::CHRONO_TEMP_10         => 'Chrono 10 (Temp)',
            self::CHRONO_ZENGO_13        => 'Chrono Zengo 13',
            self::CHRONO_ZENGO_RELAIS_13 => 'Chrono Zengo Relais 13',
            self::CHRONO_FRET_DOM        => 'Chrono Fret DOM',

            // Instances (Cas techniques rares en display client, mais utiles en log)
            self::CHRONO_13_INSTANCE_AGENCE     => 'Chrono 13 (Instance Agence)',
            self::CHRONO_13_INSTANCE_RELAIS     => 'Chrono 13 (Instance Relais)',
            self::CHRONO_10_SANS_INSTANCE_POSTE => 'Chrono 10 (Sans instance)',
            self::CHRONO_18_INSTANCE_AGENCE     => 'Chrono 18 (Instance Agence)',
            self::CHRONO_18_INSTANCE_RELAIS     => 'Chrono 18 (Instance Relais)',

            self::UNKNOWN => 'Inconnu',
        };
    }

    public function isRelayDelivery(): bool
    {
        return in_array($this, [
            self::CHRONO_RELAIS_13,
            self::CHRONO_RELAIS_13_SPECIAL,
            self::CHRONO_RELAIS_EUROPE,
            self::CHRONO_RELAIS,
            self::CHRONO_RELAIS_AMBIENT,
            self::CHRONO_9_RELAIS,
            self::CHRONO_RELAIS_DOM,
            self::CHRONO_TO_SHOP_DIRECT_PETITPROS,
            self::CHRONO_TO_SHOP_DIRECT,
            self::CHRONO_TO_SHOP_DIRECT_EUROPE,
        ], true);
    }

    public function isShop2Shop(): bool
    {
        return in_array($this, [
            self::CHRONO_TO_SHOP_DIRECT_PETITPROS,
            self::CHRONO_TO_SHOP_DIRECT,
            self::CHRONO_TO_SHOP_DIRECT_EUROPE,
        ], true);
    }

    public function isHomeDelivery(): bool
    {
        return ! $this->isRelayDelivery() &&
            $this !== self::CHRONO_RETRAIT_BUREAU;
    }

    public static function fromCode(string $code): string
    {
        $product = self::tryFrom($code);

        return $product ? $product->getLabel() : "Produit inconnu ($code)";
    }

    public static function tryFromOrUnknown(?string $code): self
    {
        if ($code === null || $code === '') {
            return self::UNKNOWN;
        }

        return self::tryFrom(str_pad($code, 2, '0', STR_PAD_LEFT)) ?? self::UNKNOWN;
    }
}
