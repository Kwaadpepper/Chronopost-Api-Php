<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Enums;

/**
 * Enumération des codes de produits Chronopost pour l'intégration API.
 * Basé sur les listes de produits/codes fournies par Chronopost.
 */
enum ChronopostProductCode: string
{
    case CHRONO_13                                                 = '01';
    case CHRONO_10                                                 = '02';
    case CHRONO_18                                                 = '16';
    case CHRONO_EXPRESS                                            = '17';
    case CHRONO_PREMIUM                                            = '37';
    case CHRONO_CLASSIC                                            = '44';
    case CHRONO_RELAIS_EUROPE                                      = '49';
    case CHRONO_13_BAL_INSTANCE_RELAIS_ET_BP                       = '58';
    case CHRONO_8                                                  = '75';
    case CHRONO_9                                                  = '76';
    case CHRONO_12                                                 = '77';
    case CHRONO_RELAIS                                             = '86';
    case CHRONO_13_INSTANCE_POSTE_OBLIGATOIRE                      = '93';
    case CHRONO_13_REMISE_PAS_DE_PORTE_POSSIBLE                    = '1F';
    case CHRONO_18_REMISE_PAS_DE_PORTE_POSSIBLE                    = '1G';
    case CHRONO_MARCHANDISES_DANGEREUSES_13                        = '1M';
    case CHRONO_MARCHANDISES_DANGEREUSES_18                        = '1N';
    case CHRONO_13_INSTANCE_AGENCE                                 = '1S';
    case CHRONO_13_INSTANCE_RELAIS                                 = '1T';
    case CHRONO_10_INSTANCE_AGENCE                                 = '1U';
    case CHRONO_18_INSTANCE_AGENCE                                 = '1V';
    case CHRONO_MEDICAL_8                                          = '2A';
    case CHRONO_MEDICAL_9                                          = '2B';
    case CHRONO_FRESH_RENDEZ_VOUS                                  = '2E';
    case CHRONO_18_BAL_INSTANCE_RELAIS_ET_BP_2                     = '2L';
    case CHRONO_RENDEZ_VOUS                                        = '2O';
    case CHRONO_FREEZE_SAME_DAY                                    = '2Q';
    case CHRONO_FRESH_13                                           = '2R';
    case CHRONO_FREEZE_13                                          = '2S';
    case CHRONO_FRET_DOM                                           = '3S';
    case CHRONO_18_INSTANCE_RELAIS                                 = '3Z';
    case CHRONO_SAME_DAY                                           = '4I';
    case CHRONO_REVERSE_10                                         = '4S';
    case CHRONO_FRESH_12                                           = '4V';
    case CHRONO_FREEZE_12                                          = '4W';
    case CHRONO_FRESH_CLASSIC                                      = '4X';
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
    case CHRONO_13_NON_CONTRACTUALISE                              = '9A';
    case CHRONO_10_NON_CONTRACTUALISE                              = '9B';
    case CHRONO_18_NON_CONTRACTUALISE                              = '9C';
    case CHRONO_EXPRESS_NON_CONTRACTUALISE                         = '9D';
    case CHRONO_PREMIUM_NON_CONTRACTUALISE                         = '9E';
    case CHRONO_CLASSIC_NON_CONTRACTUALISE_1                       = '9F';
    case CHRONO_EXPRESS_NON_CONTRACTUALISE_2                       = '9J';
    case CHRONO_CLASSIC_NON_CONTRACTUALISE_2                       = '9L';

    /**
     * Retourne le nom lisible du produit Chronopost associé à ce code de service.
     *
     * @return string Le nom complet du service Chronopost.
     *
     * @phpcs:disable Generic.Metrics.CyclomaticComplexity.MaxExceeded
     * @phpcs:disable Generic.Files.LineLength.TooLong
     */
    public function getProductName(): string
    {
        return match ($this) {
            self::CHRONO_13 => 'Chrono 13',
            self::CHRONO_10 => 'Chrono 10',
            self::CHRONO_18 => 'Chrono 18',
            self::CHRONO_EXPRESS => 'Chrono Express',
            self::CHRONO_PREMIUM => 'Chrono Premium',
            self::CHRONO_CLASSIC => 'Chrono Classic',
            self::CHRONO_RELAIS_EUROPE => 'Chrono Relais Europe',
            self::CHRONO_13_BAL_INSTANCE_RELAIS_ET_BP => 'Chrono 13 BAL (instance relais et BP)',
            self::CHRONO_8 => 'Chrono 8',
            self::CHRONO_9 => 'Chrono 9',
            self::CHRONO_12 => 'Chrono 12',
            self::CHRONO_RELAIS => 'Chrono Relais',
            self::CHRONO_13_INSTANCE_POSTE_OBLIGATOIRE => 'Chrono 13 (instance Poste obligatoire)',
            self::CHRONO_13_REMISE_PAS_DE_PORTE_POSSIBLE => 'Chrono 13 (remise pas de porte possible)',
            self::CHRONO_18_REMISE_PAS_DE_PORTE_POSSIBLE => 'Chrono 18 (remise pas de porte possible)',
            self::CHRONO_MARCHANDISES_DANGEREUSES_13 => 'Chrono Marchandises Dangereuses 13',
            self::CHRONO_MARCHANDISES_DANGEREUSES_18 => 'Chrono Marchandises Dangereuses 18',
            self::CHRONO_13_INSTANCE_AGENCE => 'Chrono 13 (instance Agence)',
            self::CHRONO_13_INSTANCE_RELAIS => 'Chrono 13 (instance Relais)',
            self::CHRONO_10_INSTANCE_AGENCE => 'Chrono 10 (instance agence)',
            self::CHRONO_18_INSTANCE_AGENCE => 'Chrono 18 (instance agence)',
            self::CHRONO_MEDICAL_8 => 'Chrono Medical 8',
            self::CHRONO_MEDICAL_9 => 'Chrono Medical 9',
            self::CHRONO_FRESH_RENDEZ_VOUS => 'Chrono Fresh Rendez-Vous',
            self::CHRONO_18_BAL_INSTANCE_RELAIS_ET_BP_2 => 'Chrono 18 BAL (instance relais et BP)',
            self::CHRONO_RENDEZ_VOUS => 'Chrono Rendez-Vous',
            self::CHRONO_FREEZE_SAME_DAY => 'Chrono Freeze Same Day',
            self::CHRONO_FRESH_13 => 'Chrono Fresh 13',
            self::CHRONO_FREEZE_13 => 'Chrono Freeze 13',
            self::CHRONO_FRET_DOM => 'Chrono Fret DOM',
            self::CHRONO_18_INSTANCE_RELAIS => 'Chrono 18 (instance Relais)',
            self::CHRONO_SAME_DAY => 'Chrono Same Day',
            self::CHRONO_REVERSE_10 => 'Chrono Reverse 10',
            self::CHRONO_FRESH_12 => 'Chrono Fresh 12',
            self::CHRONO_FREEZE_12 => 'Chrono Freeze 12',
            self::CHRONO_FRESH_CLASSIC => 'Chrono Fresh Classic',
            self::CHRONO_MEDICAL_10 => 'Chrono Medical 10',
            self::CHRONO_MEDICAL_13 => 'Chrono Medical 13',
            self::CHRONO_MEDICAL_18 => 'Chrono Medical 18',
            self::CHRONO_MEDICAL_10_THERMOSENSIBLE => 'Chrono Médical 10 thermosensible',
            self::CHRONO_MEDICAL_13_THERMOSENSIBLE => 'Chrono Médical 13 thermosensible',
            self::CHRONO_MEDICAL_18_THERMOSENSIBLE => 'Chrono Médical 18 thermosensible',
            self::CHRONO_MEDICAL_MARCHANDISES_DANGEREUSES_13 => 'Chrono Médical Marchandises Dangereuses 13',
            self::CHRONO_MEDICAL_MARCHANDISES_DANGEREUSES_18 => 'Chrono Médical Marchandises Dangereuses 18',
            self::CHRONO_MEDICAL_MARCHANDISES_DANGEREUSES_13_THERMOSENSIBLE => 'Chrono Médical Marchandises Dangereuses 13 thermosensible',
            self::CHRONO_MEDICAL_MARCHANDISES_DANGEREUSES_18_THERMOSENSIBLE => 'Chrono Médical Marchandises Dangereuses 18 thermosensible',
            self::CHRONO_13_NON_CONTRACTUALISE => 'Chrono 13 (Non Contractualisé)',
            self::CHRONO_10_NON_CONTRACTUALISE => 'Chrono 10 (Non Contractualisé)',
            self::CHRONO_18_NON_CONTRACTUALISE => 'Chrono 18 (Non Contractualisé)',
            self::CHRONO_EXPRESS_NON_CONTRACTUALISE => 'Chrono Express (Non Contractualisé)',
            self::CHRONO_PREMIUM_NON_CONTRACTUALISE => 'Chrono Premium (Non Contractualisé)',
            self::CHRONO_CLASSIC_NON_CONTRACTUALISE_1 => 'Chrono Classic (Non Contractualisé - 1)',
            self::CHRONO_EXPRESS_NON_CONTRACTUALISE_2 => 'Chrono Express (Non Contractualisé - 2)',
            self::CHRONO_CLASSIC_NON_CONTRACTUALISE_2 => 'Chrono Classic (Non Contractualisé - 2)',
        };//end match
    }
}
