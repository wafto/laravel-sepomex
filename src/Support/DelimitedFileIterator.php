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

        // Skip initial lines if specified
        for ($i = 0; $i < $this->skipLines; $i++) {
            if (! $this->file->eof()) {
                $this->file->fgets();
            }
        }
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

        return $this;
    }

    /**
     * Read headers from the next line in the file.
     *
     * @return $this
     */
    public function readHeaders(): self
    {
        if (! $this->file->eof()) {
            $line = $this->file->fgets();
            $this->headers = $this->parseLine($line);
            $this->associative = true;
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
     * Parse a line from the file.
     *
     * @param  string  $line  The line to parse
     */
    protected function parseLine(string $line): array
    {
        // Convert encoding
        $line = iconv($this->inputEncoding, $this->outputEncoding, $line);

        // Split by delimiter and clean values
        return array_map(
            function ($value) {
                $value = trim($value);

                return empty($value) ? null : $value;
            },
            explode($this->delimiter, $line)
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
     * Move forward to next element.
     */
    public function next(): void
    {
        if (! $this->file->eof()) {
            $line = $this->file->fgets();

            if ($line !== false && trim($line) !== '') {
                $row = $this->parseLine($line);

                // Convert to associative array if headers are set
                if ($this->associative && $this->headers !== null && count($row) === count($this->headers)) {
                    $this->currentRow = array_combine($this->headers, $row);
                } else {
                    $this->currentRow = $row;
                }
            } else {
                $this->currentRow = null;
            }

            $this->currentLine++;
        } else {
            $this->currentRow = null;
        }
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
        $this->currentLine = 0;
        $this->currentRow = null;

        // Skip initial lines again
        for ($i = 0; $i < $this->skipLines; $i++) {
            if (! $this->file->eof()) {
                $this->file->fgets();
            }
        }

        // If headers were read, skip them again
        if ($this->associative && $this->headers !== null) {
            if (! $this->file->eof()) {
                $this->file->fgets();
            }
        }

        // Load first row
        $this->next();
    }
}
