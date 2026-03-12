<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Exceptions\Calculate;

/**
 * @phpcs:disable Generic.CodeAnalysis.UselessOverridingMethod.Found
 */
class CalculateException extends \RuntimeException
{
    /**
     * The error code.
     */
    private string $errorCode;

    /**
     * CalculateException constructor.
     *
     * @param  string          $message  Exception message.
     * @param  integer         $code     Exception code.
     * @param  \Throwable|null $previous Previous exception.
     */
    public function __construct(
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);

        $this->errorCode = (string)$code;
    }

    /**
     * Get the error code.
     */
    public function getErrorCode(): string
    {
        return $this->errorCode;
    }
}
