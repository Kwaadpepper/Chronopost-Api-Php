<?php

declare(strict_types=1);

namespace Kwaadpepper\ChronopostApiPhp\Helpers;

class StringHelper
{
    /**
     * Normalizes a string by replacing multiple spaces with single spaces and trimming whitespace.
     * This ensures consistent spacing before processing.
     *
     * @param string $string The input string.
     * @return string The normalized string.
     */
    private static function normalizeString(string $string): string
    {
        $string = preg_replace('/\s+/', ' ', $string);
        return trim($string);
    }

    /**
     * Wraps an array of words into lines, respecting a maximum line length for each.
     * This function's primary goal is to perform word wrapping and generate as many lines
     * as necessary without imposing a line limit here.
     *
     * @param array<int, string> $words      An array of individual words.
     * @param integer            $lineLength The maximum character length allowed per line.
     * @return string[] An array of strings, where each string represents a wrapped line.
     */
    private static function wrapWordsIntoLines(array $words, int $lineLength): array
    {
        $lines       = [];
        $currentLine = '';

        foreach ($words as $word) {
            $potentialLine = ($currentLine === '') ? $word : $currentLine . ' ' . $word;

            if (strlen($potentialLine) <= $lineLength) {
                $currentLine = $potentialLine;
            } else {
                if ($currentLine !== '') {
                    $lines[] = $currentLine;
                }
                $currentLine = $word;
            }
        }

        if ($currentLine !== '') {
            $lines[] = $currentLine;
        }

        return $lines;
    }

    /**
     * Formats an array of wrapped lines to a specified maximum number of lines,
     * ensuring each line adheres to the specified line length by truncating if necessary.
     * This handles the 'word-break: break-word' behavior for long strings/words without spaces.
     *
     * @param string[] $wrappedLines An array of lines obtained from word wrapping.
     * @param integer  $lineLength   The maximum character length for each output line.
     * @param integer  $maxLines     The maximum number of lines to return.
     * @return string[] An array containing the formatted lines, up to $maxLines.
     */
    private static function formatLines(array $wrappedLines, int $lineLength, int $maxLines): array
    {
        $result = array_fill(0, $maxLines, '');

        if (empty($wrappedLines) || $maxLines <= 0) {
            return $result;
        }

        for ($i = 0; $i < $maxLines; $i++) {
            if (isset($wrappedLines[$i])) {
                if ($i < $maxLines - 1) {
                    $result[$i] = $wrappedLines[$i];
                } else {
                    $lastLineContent = implode(' ', array_slice($wrappedLines, $i));
                    $result[$i]      = $lastLineContent;
                }

                if (strlen($result[$i]) > $lineLength) {
                    $result[$i] = substr($result[$i], 0, $lineLength);
                }
            } else {
                break;
            }
        }

        return $result;
    }

    /**
     * Cuts a given string to fit into a specified number of lines,
     * each respecting a maximum character length.
     *
     * This is the primary public method. It orchestrates the process by:
     * 1. Normalizing the input string (handling multiple spaces and trimming).
     * 2. Splitting the normalized string into words.
     * 3. Wrapping these words into an array of lines based on the length constraint.
     * 4. Formatting and truncating the lines to fit the desired number of output lines,
     * even if a single word is longer than the line length (mimicking CSS `word-break: break-word`).
     *
     * @param string  $string     The original input string.
     * @param integer $lineLength The maximum character length for each output line.
     * @param integer $maxLines   The maximum number of lines to return. Must be 1 or greater.
     * @return string[] An array containing the formatted lines.
     */
    public static function cutStringToFitOnMultipleLines(string $string, int $lineLength, int $maxLines = 2): array
    {
        if ($maxLines < 1) {
            trigger_error('maxLines must be 1 or greater. Returning an empty array.', E_USER_WARNING);
            return [];
        }

        $normalizedString = self::normalizeString($string);

        if (empty($normalizedString)) {
            return array_fill(0, $maxLines, '');
        }

        $words = explode(' ', $normalizedString);

        $wrappedLines = self::wrapWordsIntoLines($words, $lineLength);

        $finalLines = self::formatLines($wrappedLines, $lineLength, $maxLines);

        return $finalLines;
    }

    /**
     * Validates a value against a regex pattern.
     *
     * @param string|null $value        The value to validate.
     * @param string      $fieldName    The name of the field for error reporting.
     * @param string      $regexPattern The regex pattern to validate against.
     * @return void
     *
     * @throws \InvalidArgumentException If the value does not match the pattern.
     */
    public static function validateValue(string|null $value, string $fieldName, string $regexPattern): void
    {
        if ($value === null) {
            return;
        }
        if (preg_match($regexPattern, $value) === false) {
            throw new \InvalidArgumentException(sprintf('Invalid %s format: %s', $fieldName, $value));
        }
    }
}
