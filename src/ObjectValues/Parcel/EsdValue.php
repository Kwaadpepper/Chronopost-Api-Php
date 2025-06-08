<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel;

use Kwaadpepper\ChronopostApiPhp\Helpers\StringHelper;

/**
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */
class EsdValue
{
    /**
     * EsdValue
     *
     * @param \DateTimeImmutable $closingDateTime               Date de clôture de l'ESD.
     * @param \DateTimeImmutable $retrievalDateTime             Date de récupération de l'ESD.
     * @param string             $shipperBuildingFloor          Étage du bâtiment de l'expéditeur, alphanumeric characters max 32.
     * @param string             $shipperCarriesCode            Code transporteur de l'expéditeur, alphanumeric characters max 38.
     * @param string             $shipperServiceDirection       Direction de service de l'expéditeur, alphanumeric characters max 32.
     * @param string             $specificInstructions          Instructions spécifiques, alphanumeric characters max 255.
     * @param string             $esdClientReference            Référence client ESD, alphanumeric characters max 32.
     * @param boolean            $ltShouldBePrintedByChronopost Should the label be printed by Chronopost, default true.
     * @param integer            $maximumPasses                 Maximum number of passes, default 1.
     *
     * @throws \InvalidArgumentException If the provided argument is invalid.
     */
    public function __construct(
        public \DateTimeImmutable $closingDateTime,
        public \DateTimeImmutable $retrievalDateTime,
        public string $shipperBuildingFloor,
        public string $shipperCarriesCode,
        public string $shipperServiceDirection,
        public string $specificInstructions,
        public string $esdClientReference,
        public bool $ltShouldBePrintedByChronopost = true,
        public int $maximumPasses = 1,
    ) {
        StringHelper::validateValue(
            $shipperBuildingFloor,
            'shipperBuildingFloor',
            '/^[a-zA-Z0-9]{0,32}$/'
        );
        StringHelper::validateValue(
            $shipperCarriesCode,
            'shipperCarriesCode',
            '/^[a-zA-Z0-9]{0,38}$/'
        );
        StringHelper::validateValue(
            $shipperServiceDirection,
            'shipperServiceDirection',
            '/^[a-zA-Z0-9]{0,32}$/'
        );
        StringHelper::validateValue(
            $specificInstructions,
            'specificInstructions',
            '/^[a-zA-Z0-9]{0,255}$/'
        );
        StringHelper::validateValue(
            $esdClientReference,
            'esdClientReference',
            '/^[a-zA-Z0-9]{0,32}$/'
        );

        if ($maximumPasses < 1) {
            throw new \InvalidArgumentException('Maximum passes must be at least 1.');
        }
    }
}
