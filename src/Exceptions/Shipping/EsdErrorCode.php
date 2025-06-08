<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping;

/**
 * Enum representing error codes for Chronopost ESD operations.
 */
enum EsdErrorCode: int
{
    case ESD_OK                          = 0;
    case SYSTEM_ERROR                    = 1;
    case APPLICATION_ERROR               = 2;
    case METHOD_INPUT_PARAMETER_EMPTY    = 3;
    case CONTACT_CLIENT_ATTRIBUTE_EMPTY  = 4;
    case PICKUP_CONTACT_ATTRIBUTE_EMPTY  = 5;
    case PICKUP_PACKAGES_ATTRIBUTE_WRONG = 6;
    case CLIENT_INFO_ATTRIBUTE_EMPTY     = 7;
    case WRONG_CONTRACT_NUMBER           = 8;
    case PICKUP_ADDRESS_ZC_UNKNOWN       = 9;
    case CONTRAINTES_NOT_FOUND           = 10;
    case CONTRAINTES_NOT_ACTIVE          = 11;
    case CONTRAINTES_ZC_CITY_UNKNOWN     = 12;
    case PICKUP_DATE_ERROR_1             = 13;
    case PICKUP_DATE_ERROR_2             = 14;
    case PICKUP_DATE_ERROR_3             = 15;
    case PICKUP_DATE_ERROR_4             = 16;
    case PICKUP_DATE_ERROR_5             = 17;
    case PICKUP_DATE_ERROR_6             = 18;
    case PICKUP_DATE_ERROR_7             = 19;
    case PICKUP_DATE_ERROR_8             = 20;
    case PICKUP_DATE_ERROR_9             = 21;
    case CONTACT_CLIENT_ZC_CITY_UNKNOWN  = 22;
    case CONTACT_CLIENT_ZC_UNKNOWN       = 23;
    case WRONG_ORIGIN_VALUE              = 24;
    case DUPLICATE_REFERENCE             = 25;
    case MODIFICATION_INVALID            = 26;
    case ANNULATION_INVALID              = 27;
    case ESD_NOT_FOUND                   = 28;
    case DATE_NON_OUVREE                 = 907;
    case PRB_CALCUL_OUVREE               = 908;
    case DATE_PASSE                      = 910;
    case WEIGHT_ERROR                    = 911;
    case MANDATORY_INSTRUCTIONS          = 912;
    case DPD_ENLEVEMENT_NON_ROUTABLE     = 916;
    case DPD_PAYS_NON_OUVERT             = 917;
    case DPD_CUTOFF                      = 918;
    case DPD_ENLEVEMENT_DESTINATAIRE     = 920;
    case CONTRAT_NON_AUTORISE            = 921;
    case DIMENSIONS_ERREUR               = 922;

    /**
     * Récupère la description en français de l'erreur.
     *
     * @return string
     *
     * @phpcs:disable Generic.Files.LineLength.TooLong
     * @phpcs:disable Generic.Metrics.CyclomaticComplexity.MaxExceeded
     */
    public function getDescription(): string
    {
        return match ($this) {
            self::ESD_OK => 'ESD créé avec succès',
            self::SYSTEM_ERROR => 'Erreur système',
            self::APPLICATION_ERROR => 'Erreur de l’application',
            self::METHOD_INPUT_PARAMETER_EMPTY => 'Attribut manquant',
            self::CONTACT_CLIENT_ATTRIBUTE_EMPTY => 'Attribut de la structure ContactClient manquant',
            self::PICKUP_CONTACT_ATTRIBUTE_EMPTY => 'Attribut de la structure ContactEnlevement manquant',
            self::PICKUP_PACKAGES_ATTRIBUTE_WRONG => 'Si le nombre de colis ou le poids du colis < 0',
            self::CLIENT_INFO_ATTRIBUTE_EMPTY => 'Attribut obligatoire manquant dans la structure infoClient',
            self::WRONG_CONTRACT_NUMBER => 'Numéro de contrat erroné',
            self::PICKUP_ADDRESS_ZC_UNKNOWN => 'Impossible d’associer le code postal/ville demandé pour l’enlèvement à une agence Chronopost.',
            self::CONTRAINTES_NOT_FOUND => 'Le code postal et/ou la ville demandé pour l’enlèvement sont inconnus',
            self::CONTRAINTES_NOT_ACTIVE => 'Le code postal/ville demandé pour l’enlèvement n’est pas désservi.',
            self::CONTRAINTES_ZC_CITY_UNKNOWN => 'La ville demandée n’est pas reconnue. Remarque : Le service est susceptible de modifier le nom de la ville, ex : SAINT TROPEZ sera modifié en ST TROPEZ',
            self::PICKUP_DATE_ERROR_1 => 'Erreur liée au calcul de la date de passage (1)',
            self::PICKUP_DATE_ERROR_2 => 'Erreur liée au calcul de la date de passage (2)',
            self::PICKUP_DATE_ERROR_3 => 'Erreur liée au calcul de la date de passage (3)',
            self::PICKUP_DATE_ERROR_4 => 'Erreur liée au calcul de la date de passage (4)',
            self::PICKUP_DATE_ERROR_5 => 'Erreur liée au calcul de la date de passage (5)',
            self::PICKUP_DATE_ERROR_6 => 'Erreur liée au calcul de la date de passage (6)',
            self::PICKUP_DATE_ERROR_7 => 'Erreur liée au calcul de la date de passage (7)',
            self::PICKUP_DATE_ERROR_8 => 'Erreur liée au calcul de la date de passage (8)',
            self::PICKUP_DATE_ERROR_9 => 'Erreur liée au calcul de la date de passage (9)',
            self::CONTACT_CLIENT_ZC_CITY_UNKNOWN => 'Non utilisé actuellement (ville client)',
            self::CONTACT_CLIENT_ZC_UNKNOWN => 'Non utilisé actuellement (code postal client)',
            self::WRONG_ORIGIN_VALUE => 'Valeur d\'origine erronée',
            self::DUPLICATE_REFERENCE => 'La référence ESD Client doit être unique pour une même date de passage',
            self::MODIFICATION_INVALID => 'Modification invalide',
            self::ANNULATION_INVALID => 'Annulation invalide',
            self::ESD_NOT_FOUND => 'ESD non trouvé',
            self::DATE_NON_OUVREE => 'La date de passage demandée n’est pas ouvrée',
            self::PRB_CALCUL_OUVREE => 'Erreur de calcul de date ouvrée',
            self::DATE_PASSE => 'La date de passage demandée est dans le passé',
            self::WEIGHT_ERROR => 'Le poids dépasse la limite autorisée de 30 kg',
            self::MANDATORY_INSTRUCTIONS => 'Si les instructions particulières sont vides dans le cas de l’option Fret DOM.',
            self::DPD_ENLEVEMENT_NON_ROUTABLE => 'Le code postal d’enlèvement n’est pas desservi pour le pays demandé',
            self::DPD_PAYS_NON_OUVERT => 'Le pays n’est pas ouvert au service',
            self::DPD_CUTOFF => 'L’heure actuelle est trop tardive pour demander un enlèvement pour la date de passage demandée.',
            self::DPD_ENLEVEMENT_DESTINATAIRE => 'Le pays doit être la FRANCE et le code postal doit être en France métropolitaine.',
            self::CONTRAT_NON_AUTORISE => 'L’offre «ESD Europe» n’a pas été contractualisée pour le contrat spécifié.',
            self::DIMENSIONS_ERREUR => 'Les dimensions du colis dépassent la règle: longueur x2 + largeur x2 + hauteur x2 ≤ 300',
        };//end match
    }
}
