<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Factory;

use ChronopostShipping\StructType\ResultGetReservedSkybillValue;
use ChronopostShipping\StructType\ResultGetReservedSkybillWithTypeValue;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\SkybillLabel;
use Kwaadpepper\ChronopostApiPhp\Dto\Shipping\TransportTicket;

class SkybillLabelFactory implements Factory
{
    /**
     * @param \ChronopostShipping\StructType\ResultGetReservedSkybillValue|\ChronopostShipping\StructType\ResultGetReservedSkybillWithTypeValue $response
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\SkybillLabel
     */
    public function create($response): SkybillLabel
    {
        return $this->createWithIdentifier($response, '');
    }

    /**
     * @param \ChronopostShipping\StructType\ResultGetReservedSkybillValue|\ChronopostShipping\StructType\ResultGetReservedSkybillWithTypeValue $response
     * @param string $identifier
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Shipping\SkybillLabel
     */
    public function createWithIdentifier(
        ResultGetReservedSkybillValue|ResultGetReservedSkybillWithTypeValue $response,
        string $identifier
    ): SkybillLabel
    {
        $pdf = $response->getSkybill();

        if ($pdf === null || $pdf === '') {
            throw new \InvalidArgumentException('Skybill label is missing');
        }

        if (!$this->isBase64($pdf)) {
            $pdf = base64_encode($pdf);
        }

        $type = $response instanceof ResultGetReservedSkybillWithTypeValue ? $response->getType() : null;

        return new SkybillLabel(
            skybillNumber: $identifier,
            transportTicket: new TransportTicket($pdf),
            type: $type,
        );
    }

    private function isBase64(string $value): bool
    {
        if ($value === '' || strlen($value) % 4 !== 0) {
            return false;
        }

        if (!preg_match('/^[A-Za-z0-9+\/]*={0,2}$/', $value)) {
            return false;
        }

        $decoded = base64_decode($value, true);
        return $decoded !== false && base64_encode($decoded) === $value;
    }
}
