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
     * @param  \Kwaadpepper\ChronopostApiPhp\Enums\ShippingType                     $objectType           Type de marchandise.
     * @param  \Kwaadpepper\ChronopostApiPhp\ObjectValues\ProductCode               $productCode          Code produit de livraison. Les produits à utiliser sont ceux contractualisés avec Chronopost.
     *                                                                                                        !Codes fournis par votre contact IT Chronopost
     * @param  \Kwaadpepper\ChronopostApiPhp\ObjectValues\ServiceCode               $serviceCode          Jour de livraison.
     *                                                                                                        !Codes fournis par votre contact IT Chronopost
     * @param  float                                                                $weight               Poids du colis en kilogrammes.
     * @param  integer                                                              $height               Hauteur colis en cm (0 si inconnu).
     * @param  integer                                                              $width                Largeur colis en cm (0 si inconnu).
     * @param  integer                                                              $length               Longueur colis en cm (0 si inconnu).
     * @param  string|null                                                          $as                   Code de livraison.
     *                                                                                                        !Codes fournis par votre contact IT Chronopost
     * @param  string|null                                                          $subAccount           Numéro de sous compte.
     * @param  string|null                                                          $toTheOrderOf         Ordre du chèque pour un contre remboursement.
     * @param  string|null                                                          $alternateProductCode Code produit de 'remplacement' en cas d'indisponibilité du produit principal.
     *                                                                                                    Les produits à utiliser sont ceux contractualisés avec Chronopost.
     * @param  \Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel\ParcelContent|null $content              Description du contenu du colis.
     *                                                                                                        ! Must be used for international shipments.
     * @param  \DateTimeImmutable|null                                              $shipDateTime         Date et heure de génération de l'envoi.
     * @param  string                                                               $codCurrency          Devise du contre remboursement EUR par défaut
     * @param  integer                                                              $codValue             Montant du contre-remboursement en centimes.
     * @param  string                                                               $customsCurrency      Devise des douanes EUR par défaut, EUR, USD, GBP.
     * @param  integer                                                              $customsValue         Valeur en centimes pour les douanes.
     * @param  string                                                               $insuredCurrency      Devise de la valeur assurrée en EUR.
     * @param  integer                                                              $insuredValue         Valeur en centimes pour l'assurance.
     * @param  string|null                                                          $masterSkybillNumber  Numéro du premier colis d'une expédition.
     * @param  integer                                                              $bulkNumber           Nombre total de colis.
     * @param  integer                                                              $skybillRank          Ordre du colis, impératif avec bulk number.
     *
     * @throws \InvalidArgumentException If any of the numeric values are negative or if the string values do not match the expected patterns.
     */
    public function __construct(
        public ShippingType $objectType,
        ProductCode $productCode,
        ServiceCode $serviceCode,
        public float $weight,
        public int $height = 0,
        public int $width = 0,
        public int $length = 0,
        public ?string $as = null,
        public ?string $subAccount = null,
        public ?string $toTheOrderOf = null,
        public ?string $alternateProductCode = null,
        public ?ParcelContent $content = null,
        public ?\DateTimeImmutable $shipDateTime = null,
        public string $codCurrency = 'EUR',
        public int $codValue = 0,
        public string $customsCurrency = 'EUR',
        public int $customsValue = 0,
        public string $insuredCurrency = 'EUR',
        public int $insuredValue = 0,
        public ?string $masterSkybillNumber = null,
        public int $bulkNumber = 1,
        public int $skybillRank = 1,
    ) {
        $this->productCode = $productCode->getValue();
        $this->productName = $productCode->getName();
        $this->serviceCode = $serviceCode->getValue();
        $this->serviceName = $serviceCode->getName();

        if ($this->codValue < 0) {
            throw new \InvalidArgumentException(
                'The codValue must be a positive integer.',
            );
        }
        if ($this->customsValue < 0) {
            throw new \InvalidArgumentException(
                'The customsValue must be a positive integer.',
            );
        }
        if ($this->insuredValue < 0) {
            throw new \InvalidArgumentException(
                'The insuredValue must be a positive integer.',
            );
        }
        StringHelper::validateValue(
            $this->productCode,
            'productCode',
            '/^[a-zA-Z0-9]{0,2}$/',
        );
        StringHelper::validateValue(
            $this->serviceCode,
            'service',
            '/^[0-9]{3}|[0-9]{1}$/',
        );
        StringHelper::validateValue(
            $this->alternateProductCode,
            'alternateProductCode',
            '/^[a-zA-Z0-9]{2}$/',
        );
    }
}
