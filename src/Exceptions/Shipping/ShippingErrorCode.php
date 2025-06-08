<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping;

/**
 * Enum representing the error codes for Chronopost shipping operations.
 */
enum ShippingErrorCode: int
{
    case PICKUP_DATE_VARIATION       = 18;
    case AVAILABILITY_DATE_VARIATION = 21;
    case UNIQUE_ESD_CLIENT_REFERENCE = 25;
    case SYSTEM_ERROR_GENERAL        = 29;
    case CONTRACT_OR_PASSWORD_ERROR  = 30;
    case MANDATORY_PARAMETER_MISSING = 31;
    case PARAMETER_TOO_LONG          = 32;
    // Erreurs de validation diverses regroupées sous le code 33.
    case VALIDATION_ERROR             = 33;
    case PARAMETER_NOT_NUMERIC        = 34;
    case GEOPOS_SERVICE_INCONSISTENCY = 35;
    case CONFIG_OR_PDF_STORAGE_ERROR  = 36;
    case SBRANGE_ALLOCATION_ERROR     = 37;
    case ROUTING_INCONSISTENCY_ERROR  = 38;

    /**
     * Récupère la description en français de l'erreur.
     *
     * @return string
     *
     * @phpcs:disable Generic.Files.LineLength.TooLong
     * @phpcs:disable Generic.Metrics.CyclomaticComplexity.TooHigh
     */
    public function getDescription(): string
    {
        return match ($this) {
            self::PICKUP_DATE_VARIATION => 'Le message varie en fonction de la date d\'enlèvement souhaitée',
            self::AVAILABILITY_DATE_VARIATION => 'Le message varie en fonction de la date d\'enlèvement souhaitée',
            self::UNIQUE_ESD_CLIENT_REFERENCE => 'La valeur du champ refEsdClient doit être unique sur un jour donné',
            self::SYSTEM_ERROR_GENERAL => 'Erreur système',
            self::CONTRACT_OR_PASSWORD_ERROR => 'Erreur dans la saisie du numéro de contrat ou du mot de passe',
            self::MANDATORY_PARAMETER_MISSING => 'Paramètre obligatoire non saisi',
            self::PARAMETER_TOO_LONG => 'Paramètre saisi trop long',
            self::VALIDATION_ERROR => 'Erreur de validation. Cela peut inclure : le champ accountNumber n\'est pas renseigné correctement, le champ password n\'est pas renseigné correctement, le champ evtCode n\'est pas renseigné correctement, le champ service n\'est pas renseigné correctement, le champ objectType n\'est pas renseigné correctement, le champ mode n\'est pas renseigné correctement, le champ productCode doit contenir 2 caractères alphanumériques, l\'expédition multi-colis : numberOfParcel ne comporte pas la bonne quantité, le champ multiParcel doit être à \'N\' lorsqu\'il y a plusieurs recipientValue, ou le champ codValue n\'est pas renseigné.',
            self::PARAMETER_NOT_NUMERIC => 'Le paramètre saisi doit être numérique',
            self::GEOPOS_SERVICE_INCONSISTENCY => 'Erreur dans la cohérence produit, Pays expéditeur, CP expéditeur, Pays destinataire, CP destinataire',
            self::CONFIG_OR_PDF_STORAGE_ERROR => 'Erreur dans les fichiers de configuration ou au stockage du PDF',
            self::SBRANGE_ALLOCATION_ERROR => 'Message d’erreur système du service d’allocation des numéros de colis (SBrange)',
            self::ROUTING_INCONSISTENCY_ERROR => 'Erreur dans la cohérence produit, Pays expéditeur, CP expéditeur, Pays destinataire, CP destinataire',
        };
    }
}
