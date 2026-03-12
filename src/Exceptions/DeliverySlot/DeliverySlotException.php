<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Exceptions\DeliverySlot;

class DeliverySlotException extends \RuntimeException
{
    /**
     * Throws an exception if the WsResponse code indicates an error.
     *
     * @param integer $code    The response code (0 = success).
     * @param string  $message The response message.
     * @return void
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\DeliverySlot\DeliverySlotException
     */
    public static function throwIfError(int $code, string $message = ''): void
    {
        if ($code !== 0) {
            throw new self(
                $message ?: 'A delivery slot error occurred.',
                $code,
            );
        }
    }
}
