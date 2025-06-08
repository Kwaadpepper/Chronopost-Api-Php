<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping;

use Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingErrorCode;

class ShippingException extends \RuntimeException
{
    /**
     * The error code.
     *
     * @var \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingErrorCode
     */
    private ShippingErrorCode $errorCode;

    /**
     * ShippingException constructor.
     *
     * @param string          $message  Exception message.
     * @param integer         $code     Exception code.
     * @param \Throwable|null $previous Previous exception.
     */
    public function __construct(
        string $message = '',
        int $code = 0,
        \Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);

        $this->errorCode = ShippingErrorCode::from($code);
    }

    /**
     * Get the error code.
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\ShippingErrorCode
     */
    public function getErrorCode(): ShippingErrorCode
    {
        return $this->errorCode;
    }
}
