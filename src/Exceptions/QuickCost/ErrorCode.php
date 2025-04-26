<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Exceptions\QuickCost;

enum ErrorCode: int
{
    case SYSTEM_ERROR         = 1;
    case DATA_EMPTY           = 2;
    case INVALID_PASSWORD     = 3;
    case INVALID_PRODUCT_CODE = 4;
    case AMOUNT_NOT_FOUND     = 5;

    /**
     * Get the error code.
     *
     * @return integer The error code.
     */
    public function getCode(): int
    {
        return $this->value;
    }

    /**
     * Get the error message.
     *
     * @return string The error message.
     * 4.3.6. Codes erreurs pour le service ‘quickCost’
     */
    public function getTitle(): string
    {
        return match ($this) {
            self::SYSTEM_ERROR => 'System Error',
            self::DATA_EMPTY => 'Data Empty',
            self::INVALID_PASSWORD => 'Invalid Password',
            self::INVALID_PRODUCT_CODE => 'Invalid product code for this destination',
            self::AMOUNT_NOT_FOUND => 'Amount not found',
        };
    }

    /**
     * Get the error description.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return match ($this) {
            self::SYSTEM_ERROR => 'Erreur système',
            self::DATA_EMPTY => 'Il manque un paramètre obligatoire',
            self::INVALID_PASSWORD => 'Le mot de passe ne correspond pas au numéro de contrat',
            // phpcs:disable Generic.Files.LineLength.TooLong
            self::INVALID_PRODUCT_CODE => 'Le code produit entré n’est pas cohérent avec les codes de départ et d’arrivée',
            self::AMOUNT_NOT_FOUND => 'Il n’y a pas de résultat pour les données entrées',
        };
    }
}
