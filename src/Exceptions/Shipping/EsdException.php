<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping;

class EsdException extends \RuntimeException
{
    /**
     * The error code.
     *
     * @var \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\EsdErrorCode
     */
    private EsdErrorCode $errorCode;

    /**
     * EsdException constructor.
     *
     * @param string          $message  Exception message.
     * @param integer         $code     Exception code.
     * @param \Throwable|null $previous Previous exception.
     */
    public function __construct(
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);

        $this->errorCode = EsdErrorCode::from($code);
    }

    /**
     * Get the error code.
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\EsdErrorCode
     */
    public function getErrorCode(): EsdErrorCode
    {
        return $this->errorCode;
    }

    /**
     * Throws an exception if the provided code is an ESD error code.
     *
     * @param integer $code    The error code to check.
     * @param string  $message Optional message for the exception.
     * @return void
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Shipping\EsdException If the code is an ESD error code.
     */
    public static function throwIfEsdError(int $code, string $message = ''): void
    {
        try {
            EsdErrorCode::from($code);
            throw new self(
                $message ?: 'An ESD error occurred.',
                $code
            );
        } catch (\ValueError $e) {
            // Not an ESD error code, do nothing.
            return;
        }
    }
}
