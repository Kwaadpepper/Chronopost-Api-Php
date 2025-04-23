<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Exceptions\QuickCost;

enum ErrorCodes: int
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
     */
    public function getDescription(): string
    {
        return match ($this) {
            self::SYSTEM_ERROR => 'System Error',
            self::DATA_EMPTY => 'Data Empty',
            self::INVALID_PASSWORD => 'Invalid Password',
            self::INVALID_PRODUCT_CODE => 'Invalid product code for this destination',
            self::AMOUNT_NOT_FOUND => 'Amount not found',
        };
    }
}
