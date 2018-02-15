<?php

namespace Aftab\Sepomex\Console;

use SplFileObject;
use Illuminate\Console\Command;
use Aftab\Sepomex\Models\Sepomex;

/**
 * Class ImporterCommand.
 */
class ImporterCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sepomex:import {--chunk=50}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports sepomex data from the txt file provided by "Correos de México".';

    /**
     * Truncate database data.
     *
     * @param  \Aftab\Sepomex\Models\Sepomex $model
     * @return void
     */
    protected function truncateTable(Sepomex $model)
    {
        $this->comment('Truncating table...');
        $model->truncate();
        $this->info('Table truncated.');
    }

    /**
     * Start importing and return the inserted rows count.
     *
     * @param \Aftab\Sepomex\Models\Sepomex $model
     * @param mixed $source
     * @param integer $lines
     * @param array $keys
     * @return integer
     */
    protected function startImport($model, $source, $lines, $keys)
    {
        $this->comment(sprintf('Parsing [%s] rows from file...', $lines));

        $chunk = intval($this->option('chunk'));
        $bar = $this->output->createProgressBar($lines);
        $accumulator = [];
        $inserted = 0;
        $keyCount = count($keys);

        while ($source->valid()) {
            for ($i = 0; $source->valid() && $i < $chunk; $i++) {
                $data = $this->prepareRow($source->fgets());
                if (count($data) === $keyCount) {
                    $accumulator[] = array_combine($keys, $data);
                }
            }

            $count = count($accumulator);
            $model->insert($accumulator);
            $bar->advance($count);
            $inserted += $count;
            $accumulator = [];
        }

        $bar->finish();

        return $inserted;
    }

    /**
     * Execute the console command.
     *
     * @param  \Aftab\Sepomex\Models\Sepomex $model
     * @return void
     */
    public function handle(Sepomex $model)
    {
        try {
            $chunkSize = intval($this->option('chunk'));

            // Source file.
            $source = $this->getSource();

            $lines = $this->fileRowCount();

            // Ignore first line that contain some usages & restrictions.
            $source->fgets();

            // Get the columns fields names.
            $keys = $this->prepareRow($source->fgets());

            $this->truncateTable($model);

            $lines -= 2;

            // Starting import.
            $inserted = $this->startImport($model, $source, $lines, $keys);

            $info = sprintf("\nInserted [%s] rows from [%s] file lines in %s table.", $inserted, $lines, $model->getTable());

            $this->info($info);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * Get the total lines from the file.
     *
     * @return int
     */
    protected function fileRowCount()
    {
        $count = 0;
        $path = config('sepomex.source_file');

        $handle = fopen($path, 'r');
        while (! feof($handle)) {
            fgets($handle);
            $count++;
        }

        fclose($handle);

        return $count;
    }

    /**
     * Get a File Object from the cpdescarga.txt file under package storage directory.
     *
     * @throws \Exception
     * @return SplFileObject
     */
    protected function getSource()
    {
        $path = config('sepomex.source_file');

        if (file_exists($path)) {
            return new SplFileObject($path, 'r');
        }

        throw new \Exception(sprintf('No source file found on %s, please make sure to download it.', $path));
    }

    /**
     * Parse a line from the source file.
     *
     * @param  string $str
     * @return array
     */
    protected function prepareRow($str)
    {
        return array_map(
            function ($value) {
                $value = trim($value);

                return empty($value) ? null : $value;
            }, explode('|', iconv('iso-8859-1', 'utf-8', $str))
        );
    }
}
