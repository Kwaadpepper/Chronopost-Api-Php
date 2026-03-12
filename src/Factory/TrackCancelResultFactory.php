<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Factory;

use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\CancelResult;

class TrackCancelResultFactory implements Factory
{
    /**
     * Create a CancelResult from a ResultCancelSkybill.
     *
     * @param \ChronopostTracking\StructType\ResultCancelSkybill $result
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\CancelResult
     * @phpcs:disable Squiz.Commenting.FunctionComment.TypeHintMissing
     */
    public function create($result)
    {
        // phpcs:enable
        return new CancelResult(
            errorCode: (int) $result->getErrorCode(),
            errorMessage: (string) $result->getErrorMessage(),
            statusCode: (int) $result->getStatusCode(),
        );
    }
}
