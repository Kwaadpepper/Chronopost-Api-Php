<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Factory;

use Kwaadpepper\ChronopostApiPhp\Dto\Tracking\CancelListResult;

class TrackCancelListResultFactory implements Factory
{
    /**
     * Create a CancelListResult from a ResultListCancelSkybill.
     *
     * @param \ChronopostTracking\StructType\ResultListCancelSkybill $result
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Tracking\CancelListResult
     * @phpcs:disable Squiz.Commenting.FunctionComment.TypeHintMissing
     */
    public function create($result)
    {
        // phpcs:enable
        return new CancelListResult(
            errorCode: (int)$result->getErrorCode(),
            errorMessage: (string)$result->getErrorMessage(),
            statusCode: (int)$result->getStatusCode(),
            skybills: $result->getSkybills() ?? [],
        );
    }
}
