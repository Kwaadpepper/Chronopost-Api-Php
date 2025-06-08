<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Dto\Shipping;

use Kwaadpepper\ChronopostApiPhp\Dto\Dto;

readonly class TransportTicket implements Dto
{
    /**
     * Transport Ticket
     *
     * @param string $base64 Base64 encoded transport ticket of a pdf.
     */
    public function __construct(
        public string $base64,
    ) {
    }

    /**
     * Convert the transport ticket to a pdf file.
     *
     * @param string $fileName The name of the file to create.
     *
     * @return \SplFileInfo The file info of the created file.
     * @throws \RuntimeException If the file could not be created or written to.
     * @throws \RuntimeException If the base64 content could not be decoded.
     * @throws \RuntimeException If the file was written with 0 bytes but the content was not empty.
     */
    public function toFile(string $fileName): \SplFileInfo
    {
        $fileInfo  = pathinfo($fileName);
        $directory = sys_get_temp_dir();

        $baseName  = $fileInfo['filename'];
        $extension = isset($fileInfo['extension']) ? strtolower($fileInfo['extension']) : '';

        $fileName = ($extension !== 'pdf') ? "{$baseName}.pdf" : "{$baseName}.{$extension}";

        $filePath = $directory . '/' . $fileName;

        /** @var false|string */
        $decodedContent = base64_decode($this->base64);

        if (is_bool($decodedContent)) {
            throw new \RuntimeException(sprintf('Failed to decode base64 content for file: %s', $fileName));
        }


        $bytesWritten = file_put_contents($filePath, $decodedContent);

        if ($bytesWritten === false) {
            throw new \RuntimeException(sprintf('Failed to write transport ticket to file: %s', $filePath));
        }

        if ($bytesWritten === 0 && !empty($decodedContent)) {
            throw new \RuntimeException(sprintf(
                'Wrote 0 bytes to file %s, but content was not empty. Possible disk full or permission issue.',
                $filePath
            ));
        }

        return new \SplFileInfo($filePath);
    }
}
