<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Factory;

/**
 * @phpcs:disable Squiz.Commenting.FunctionComment.TypeHintMissing
 */
interface Factory
{
    /**
     * Create a new instance of the given class.
     *
     * @param \WsdlToPhp\PackageBase\AbstractStructBase $response
     *
     * @return \Kwaadpepper\ChronopostApiPhp\Dto\Dto
     */
    public function create($response);
}
