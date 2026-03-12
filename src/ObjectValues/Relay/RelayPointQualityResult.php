<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues\Relay;

enum RelayPointQualityResult: int
{
    case BAD_QUALITY       = 0;
    case MEDIUM_QUALITY    = 1;
    case EXCELLENT_QUALITY = 2;

    /**
     * Get the quality result as a string.
     *
     * @return string The quality result as a string.
     */
    public function getQualityResult(): string
    {
        return match ($this) {
            self::BAD_QUALITY => 'Mauvaise qualité',
            self::MEDIUM_QUALITY => 'Qualité moyenne',
            self::EXCELLENT_QUALITY => 'Excellente qualité',
        };
    }

    /**
     * Get the quality result description.
     *
     * @return string The quality result description.
     */
    public function getDescription(): string
    {
        return match ($this) {
            self::BAD_QUALITY => 'Résultat à ignorer',
            self::MEDIUM_QUALITY => 'La recherche a été réalisée sur le code postal destinataire',
            self::EXCELLENT_QUALITY => 'La recherche a été réalisée sur l\'adresse destinataire',
        };
    }
}
