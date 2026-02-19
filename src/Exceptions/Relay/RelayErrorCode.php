<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Exceptions\Relay;

/**
 * Enum representing error codes for Chronopost ESD operations.
 */
enum RelayErrorCode: int
{
    case WRONG_PARAMETER = 300;
    case PARAMETER_NULL = 301;
    case WRONG_POSTAL_CODE = 302;
    case WRONG_DATE_FORMAT = 306;
    case WRONG_DATE_VALUE = 307;
    case NUMBER_NOT_STRING = 309;
    case HOLIDAY_TOLERANT_NOT_1 = 317;
    case MAX_DISTANCE_SEARCH_99 = 319;
    case MAX_POINTS_RETURNED_25 = 320;
    case WRONG_WEIGHT_FORMAT = 321;
    case NO_POINTS_FOUND = 601;
    case SYSTEM_ERROR = 700;
    case WRONG_CONTRACT_OR_PASSWORD = 1500;

    /**
     * Récupère la description en français de l'erreur.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return match ($this) {
            self::WRONG_PARAMETER => 'Un paramètre fourni au service web n\'a pas le bon format',
            self::PARAMETER_NULL => 'Un paramètre obligatoire est positionné à NULL',
            self::WRONG_POSTAL_CODE => 'Le format du code postal est incorrect',
            self::WRONG_DATE_FORMAT => 'Le format de la date est incorrect',
            self::WRONG_DATE_VALUE => 'La valeur de la date est incorrecte',
            self::NUMBER_NOT_STRING => 'Un paramètre de type nombre n\'est pas passé sous la forme d\'un nombre',
            self::HOLIDAY_TOLERANT_NOT_1 => 'Le paramètre holidayTolerant n\'est pas positionné à 1',
            self::MAX_DISTANCE_SEARCH_99 => 'La valeur maximum pour maxDistanceSearch est 99 Km',
            self::MAX_POINTS_RETURNED_25 => 'La valeur maximum de point CHR retournés ne peut pas dépasser 25 points',
            self::WRONG_WEIGHT_FORMAT => 'Le format du poids est incorrect',
            self::NO_POINTS_FOUND => 'Pas de points CHR trouvés',
            self::SYSTEM_ERROR => 'Erreur système',
            self::WRONG_CONTRACT_OR_PASSWORD => 'Erreur dans la saisie du n° de contrat ou du mot de passe',
        };//end match
    }
}
