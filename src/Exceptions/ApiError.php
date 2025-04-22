<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Exceptions;

class ApiError extends \RuntimeException
{
    /**
     * Constructor
     *
     * @param string          $message  The error message.
     * @param \Throwable|null $previous The previous exception.
     */
    public function __construct(
        string $message = 'An error occurred while processing the request.',
        \Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);
    }
}
