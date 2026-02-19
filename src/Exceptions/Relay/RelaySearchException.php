<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Exceptions\Relay;

class RelaySearchException extends \RuntimeException
{
    /**
     * The error code.
     *
     * @var \Kwaadpepper\ChronopostApiPhp\Exceptions\Relay\RelayErrorCode
     */
    private RelayErrorCode $errorCode;

    /**
     * RelaySearchException constructor.
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

        $this->errorCode = RelayErrorCode::from($code);
    }

    /**
     * Get the error code.
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Exceptions\Relay\RelayErrorCode
     */
    public function getErrorCode(): RelayErrorCode
    {
        return $this->errorCode;
    }

    /**
     * Throws an exception if the provided code is a Relay error code.
     *
     * @param integer $code    The error code to check.
     * @param string  $message Optional message for the exception.
     * @return void
     *
     * @throws \Kwaadpepper\ChronopostApiPhp\Exceptions\Relay\RelaySearchException If the code is a Relay error code.
     */
    public static function throwIfRelayError(int $code, string $message = ''): void
    {
        try {
            RelayErrorCode::from($code);
            throw new self(
                $message ?: 'A Relay error occurred.',
                $code
            );
        } catch (\ValueError $e) {
            // Not a Relay error code, do nothing.
            return;
        }
    }
}
