<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel;

use Kwaadpepper\ChronopostApiPhp\Enums\ShippingType;
use Kwaadpepper\ChronopostApiPhp\Helpers\StringHelper;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ProductCode;
use Kwaadpepper\ChronopostApiPhp\ObjectValues\ServiceCode;

/**
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */
readonly class SkyBillValue
{
    /**
     * Code produit de livraison. Les produits à utiliser sont ceux contractualisés avec Chronopost.
     * !Codes fournis par votre contact IT Chronopost
     *
     * @var string
     * @see \Kwaadpepper\ChronopostApiPhp\Enums\ChronopostProductCode
     */
    public string $productCode;

    /**
     * Nom du produit de livraison.
     * !Codes fournis par votre contact IT Chronopost
     *
     * @var string
     * @see \Kwaadpepper\ChronopostApiPhp\Enums\ChronopostProductCode
     */
    public string $productName;

    /**
     * Code de service de livraison.
     * !Codes fournis par votre contact IT Chronopost
     *
     * @var string
     * @see \Kwaadpepper\ChronopostApiPhp\Enums\DeliveryServiceCode
     */
    public string $serviceCode;

    /**
     * Nom du service de livraison.
     * !Codes fournis par votre contact IT Chronopost
     *
     * @var string
     * @see \Kwaadpepper\ChronopostApiPhp\Enums\DeliveryServiceCode
     */
    public string $serviceName;

    /**
     * SkyBillValue constructor.
     *
     * @param \Kwaadpepper\ChronopostApiPhp\Enums\ShippingType                     $objectType
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\ProductCode               $productCode
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\ServiceCode               $serviceCode
     * @param float                                                                $weight
     * @param integer                                                              $height
     * @param integer                                                              $width
     * @param integer                                                              $length
     * @param string|null                                                          $as
     * @param string|null                                                          $subAccount
     * @param string|null                                                          $toTheOrderOf
     * @param string|null                                                          $alternateProductCode
     * @param \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ParcelContent|null $content
     * @param \DateTimeImmutable|null                                              $shipDateTime
     * @param string                                                               $codCurrency
     * @param integer                                                              $codValue
     * @param string                                                               $customsCurrency
     * @param integer                                                              $customsValue
     * @param string                                                               $insuredCurrency
     * @param integer                                                              $insuredValue
     * @param string|null                                                          $masterSkybillNumber
     * @param integer                                                              $bulkNumber
     * @param integer                                                              $skybillRank
     * @throws \InvalidArgumentException If any of the numeric values are negative or if the string values do not match the expected patterns.
     */
    public function __construct(
        public ShippingType $objectType,
        /**
         * Code produit de livraison. Les produits à utiliser sont ceux contractualisés avec Chronopost.
         * !Codes fournis par votre contact IT Chronopost
         */
        ProductCode $productCode,
        /**
         * Jour de livraison
         * !Codes fournis par votre contact IT Chronopost
         */
        ServiceCode $serviceCode,
        /** Poids du colis en kilogrammes */
        public float $weight,
        /** Hauteur colis en cm (0 si inconnu)  */
        public int $height = 0,
        /** Largeur colis en cm (0 si inconnu) */
        public int $width = 0,
        /** Longueur colis en cm (0 si inconnu) */
        public int $length = 0,
        /**
         * Code de livraison
         * !Codes fournis par votre contact IT Chronopost
         */
        public string|null $as = null,
        /** Numéro de sous compte */
        public string|null $subAccount = null,
        /** Ordre du chèque pour un contre remboursement */
        public string|null $toTheOrderOf = null,
        /** Code produit de « remplacement », Les produits à utiliser sont ceux contractualisés avec Chronopost */
        public string|null $alternateProductCode = null,
        /**
         * ! Must be used for international shipments.
         * Description du contenu du colis.
         */
        public ParcelContent|null $content = null,
        /** Date et heure de génération de l'envoi */
        public \DateTimeImmutable|null $shipDateTime = null,
        /** Devise du contre remboursement EUR par défaut */
        public string $codCurrency = 'EUR',
        /** Montant du contre-remboursement en centimes */
        public int $codValue = 0,
        /** Devise des douanes EUR par défaut, EUR, USD, GBP */
        public string $customsCurrency = 'EUR',
        /** Valeur en centimes pour les douanes */
        public int $customsValue = 0,
        /** Devise de la valeur assurrée en EUR */
        public string $insuredCurrency = 'EUR',
        /** Valeur en centimes pour l'assurance */
        public int $insuredValue = 0,
        /** Numéro du premier colis d'une expédition */
        public string|null $masterSkybillNumber = null,
        /** Nombre total de colis */
        public int $bulkNumber = 1,
        /** Ordre du colis, impératif avec bulk number */
        public int $skybillRank = 1,
    ) {
        $this->productCode = $productCode->getValue();
        $this->productName = $productCode->getName();
        $this->serviceCode = $serviceCode->getValue();
        $this->serviceName = $serviceCode->getName();

        if ($this->codValue < 0) {
            throw new \InvalidArgumentException(
                'The codValue must be a positive integer.'
            );
        }
        if ($this->customsValue < 0) {
            throw new \InvalidArgumentException(
                'The customsValue must be a positive integer.'
            );
        }
        if ($this->insuredValue < 0) {
            throw new \InvalidArgumentException(
                'The insuredValue must be a positive integer.'
            );
        }
        StringHelper::validateValue(
            $this->productCode,
            'productCode',
            '/^[a-zA-Z0-9]{0,2}$/'
        );
        StringHelper::validateValue(
            $this->serviceCode,
            'service',
            '/^[0-9]{3}|[0-9]{1}$/'
        );
        StringHelper::validateValue(
            $this->alternateProductCode,
            'alternateProductCode',
            '/^[a-zA-Z0-9]{2}$/'
        );
    }
}
