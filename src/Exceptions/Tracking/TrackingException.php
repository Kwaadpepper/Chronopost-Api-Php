<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Exceptions;

/**
 * @phpcs:disable Generic.CodeAnalysis.UselessOverridingMethod.Found
 */
class TrackingException extends \RuntimeException
{
    /**
     * TrackingException constructor.
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
    }
}
