<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\QuickCost;

use Kwaadpepper\ChronopostApiPhp\Dto\Dto;
use Money\Money;

/**
 * Supplément liée à un envoi.
 *
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */
class Supplement implements Dto
{
    /**
     * @param \Money\Money                                               $amount    Montant hors taxe du supplément trouvé.
     * @param \Money\Money                                               $amountTtc Montant TTC du supplément trouvé.
     * @param \Money\Money                                               $amountTva Montant de la TVA du supplément trouvé.
     * @param \Kwaadpepper\ChronopostApiPhp\Dto\QuickCost\SupplementType $type      Type du supplément trouvé.
     */
    public function __construct(
        public Money $amount,
        public Money $amountTtc,
        public Money $amountTva,
        public SupplementType $type,
    ) {
    }
}
