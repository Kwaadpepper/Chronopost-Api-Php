<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Exceptions\QuickCost;

use Kwaadpepper\ChronopostApiPhp\Exceptions\QuickCost\ErrorCode;

/**
 * @phpcs:disable Generic.CodeAnalysis.UselessOverridingMethod.Found
 */
class QuickCostException extends \RuntimeException
{
    /**
     * The error code.
     *
     * @var \Kwaadpepper\ChronopostApiPhp\Exceptions\QuickCost\ErrorCode
     */
    private ErrorCode $errorCode;

    /**
     * QuickCostException constructor.
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

        $this->errorCode = ErrorCode::from($code);
    }

    /**
     * Get the error code.
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Exceptions\QuickCost\ErrorCode
     */
    public function getErrorCode(): ErrorCode
    {
        return $this->errorCode;
    }
}
