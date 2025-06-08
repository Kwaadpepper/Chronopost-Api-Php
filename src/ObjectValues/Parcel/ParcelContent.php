<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\ObjectValues\Parcel;

use Kwaadpepper\ChronopostApiPhp\Helpers\StringHelper;

/**
 * Represents the content of a parcel.
 *
 * ! This class is used to describe the content of a parcel,
 * ! which is required for parcel inspection on international shipments.
 */
readonly class ParcelContent
{
    /**
     * Content descriptions for the parcel.
     *
     * @var string
     */
    public string $content1;

    /**
     * Additional content descriptions for the parcel.
     *
     * @var string
     */
    public string $content2;

    /**
     * Further content descriptions for the parcel.
     *
     * @var string
     */
    public string $content3;

    /**
     * More content descriptions for the parcel.
     *
     * @var string
     */
    public string $content4;

    /**
     * Final content descriptions for the parcel.
     *
     * @var string
     */
    public string $content5;

    /**
     * ParcelContent
     *
     * @param string $content Description of the parcel content, maximum 220 characters.
     *
     * @throws \InvalidArgumentException If the provided content is invalid.
     */
    public function __construct(
        string $content
    ) {
        StringHelper::validateValue(
            $content,
            'content',
            '/^[a-zA-Z0-9]{0,220}$/'
        );

        $descriptionsLines = StringHelper::cutStringToFitOnMultipleLines($content, 45, 5);
        $this->content1    = $descriptionsLines[0] ?? '';
        $this->content2    = $descriptionsLines[1] ?? '';
        $this->content3    = $descriptionsLines[2] ?? '';
        $this->content4    = $descriptionsLines[3] ?? '';
        $this->content5    = $descriptionsLines[4] ?? '';

        if (count($descriptionsLines) > 5) {
            throw new \InvalidArgumentException('Content exceeds the maximum allowed length of 220 characters.');
        }

        if (
            strlen($this->content1) > 45 || strlen($this->content2) > 45 ||
            strlen($this->content3) > 45 || strlen($this->content4) > 45 ||
            strlen($this->content5) > 45
        ) {
            throw new \InvalidArgumentException('Each content line must not exceed 45 characters.');
        }
    }
}
