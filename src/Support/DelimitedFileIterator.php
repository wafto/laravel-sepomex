<?php

namespace Wafto\Sepomex\Support;

use Iterator;
use SplFileObject;

/**
 * Delimited file iterator for reading pipe-delimited or CSV files.
 *
 * This class implements Iterator to provide a clean interface for reading
 * delimited files with encoding conversion support.
 */
class DelimitedFileIterator implements Iterator
{
    /**
     * The file object.
     */
    protected SplFileObject $file;

    /**
     * The delimiter character.
     */
    protected string $delimiter;

    /**
     * Input encoding.
     */
    protected string $inputEncoding;

    /**
     * Output encoding.
     */
    protected string $outputEncoding;

    /**
     * Current line number.
     */
    protected int $currentLine = 0;

    /**
     * Physical line number of the last line read from the file.
     */
    protected int $lineNumber = 0;

    /**
     * Current row data.
     */
    protected ?array $currentRow = null;

    /**
     * Number of lines to skip from the beginning.
     */
    protected int $skipLines;

    /**
     * Header columns.
     */
    protected ?array $headers = null;

    /**
     * Whether to return associative arrays.
     */
    protected bool $associative = false;

    /**
     * Whether the headers were read from the file (and must be skipped on rewind).
     */
    protected bool $headersFromFile = false;

    /**
     * Create a new DelimitedFileIterator instance.
     *
     * @param  string  $filePath  Path to the delimited file
     * @param  string  $delimiter  Field delimiter (default: '|')
     * @param  string  $inputEncoding  Input file encoding (default: 'iso-8859-1')
     * @param  string  $outputEncoding  Output encoding (default: 'utf-8')
     * @param  int  $skipLines  Number of lines to skip from the beginning (default: 0)
     *
     * @throws \Exception
     */
    public function __construct(
        string $filePath,
        string $delimiter = '|',
        string $inputEncoding = 'iso-8859-1',
        string $outputEncoding = 'utf-8',
        int $skipLines = 0
    ) {
        if (! file_exists($filePath)) {
            throw new \Exception(sprintf('No source file found on %s, please make sure to download it.', $filePath));
        }

        $this->file = new SplFileObject($filePath, 'r');
        $this->delimiter = $delimiter;
        $this->inputEncoding = $inputEncoding;
        $this->outputEncoding = $outputEncoding;
        $this->skipLines = $skipLines;

        $this->skipInitialLines();
    }

    /**
     * Set headers for associative array output.
     *
     * @param  array  $headers  Column headers
     * @return $this
     */
    public function withHeaders(array $headers): self
    {
        $this->headers = $headers;
        $this->associative = true;
        $this->headersFromFile = false;

        return $this;
    }

    /**
     * Read headers from the next line in the file.
     *
     * @return $this
     */
    public function readHeaders(): self
    {
        $line = $this->readLine();

        if ($line !== null) {
            $this->headers = $this->parseLine($line);
            $this->associative = true;
            $this->headersFromFile = true;
        }

        return $this;
    }

    /**
     * Get the headers.
     */
    public function getHeaders(): ?array
    {
        return $this->headers;
    }

    /**
     * Count total lines in the file (excluding skipped lines).
     */
    public function countLines(): int
    {
        $count = 0;
        $handle = fopen($this->file->getPathname(), 'r');

        while (fgets($handle) !== false) {
            $count++;
        }

        fclose($handle);

        return max(0, $count - $this->skipLines);
    }

    /**
     * Read the next physical line, or null at end of file.
     */
    protected function readLine(): ?string
    {
        if ($this->file->eof()) {
            return null;
        }

        $line = $this->file->fgets();
        $this->lineNumber++;

        return $line === false ? null : $line;
    }

    protected function skipInitialLines(): void
    {
        for ($i = 0; $i < $this->skipLines; $i++) {
            $this->readLine();
        }
    }

    /**
     * Parse a line from the file.
     *
     * @throws \UnexpectedValueException
     */
    protected function parseLine(string $line): array
    {
        $converted = @iconv($this->inputEncoding, $this->outputEncoding, $line);

        if ($converted === false) {
            throw new \UnexpectedValueException(sprintf(
                'Unable to convert line %d from %s to %s.',
                $this->lineNumber,
                $this->inputEncoding,
                $this->outputEncoding,
            ));
        }

        return array_map(
            function (string $value) {
                $value = trim($value);

                return $value === '' ? null : $value;
            },
            explode($this->delimiter, $converted)
        );
    }

    /**
     * Get the current element.
     */
    public function current(): ?array
    {
        return $this->currentRow;
    }

    /**
     * Move forward to next element, skipping blank lines.
     *
     * @throws \UnexpectedValueException
     */
    public function next(): void
    {
        $this->currentRow = null;

        while (($line = $this->readLine()) !== null) {
            if (trim($line) === '') {
                continue;
            }

            $this->currentRow = $this->toRow($this->parseLine($line));
            $this->currentLine++;

            return;
        }
    }

    /**
     * Combine the values with the headers when in associative mode.
     *
     * @throws \UnexpectedValueException
     */
    protected function toRow(array $values): array
    {
        if (! $this->associative || $this->headers === null) {
            return $values;
        }

        if (count($values) !== count($this->headers)) {
            throw new \UnexpectedValueException(sprintf(
                'Line %d has %d columns, expected %d.',
                $this->lineNumber,
                count($values),
                count($this->headers),
            ));
        }

        return array_combine($this->headers, $values);
    }

    /**
     * Return the key of the current element.
     */
    public function key(): int
    {
        return $this->currentLine;
    }

    /**
     * Checks if current position is valid.
     */
    public function valid(): bool
    {
        return $this->currentRow !== null;
    }

    /**
     * Rewind the Iterator to the first element.
     */
    public function rewind(): void
    {
        $this->file->rewind();
        $this->lineNumber = 0;
        $this->currentLine = 0;
        $this->currentRow = null;

        $this->skipInitialLines();

        // If headers were read from the file, skip them again
        if ($this->headersFromFile) {
            $this->readLine();
        }

        // Load first row
        $this->next();
    }
}
