<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping;

class PickupException extends \RuntimeException
{
    private EsdErrorCode $errorCode;

    public function __construct(
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);

        $this->errorCode = EsdErrorCode::from($code);
    }

    public function getErrorCode(): EsdErrorCode
    {
        return $this->errorCode;
    }
}
