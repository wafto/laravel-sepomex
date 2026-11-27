<?php

namespace Wafto\Sepomex\Console;

use Illuminate\Console\Command;
use Wafto\Sepomex\Models\Sepomex;
use Wafto\Sepomex\Repositories\CachedRepository;
use Wafto\Sepomex\Support\DelimitedFileIterator;

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
     * Delete existing rows. Uses delete() instead of truncate() because
     * truncate causes an implicit commit on MySQL and cannot be rolled back.
     */
    protected function clearTable(Sepomex $model): void
    {
        $this->comment('Clearing table...');
        $model->newQuery()->delete();
        $this->info('Table cleared.');
    }

    /**
     * Start importing and return the inserted rows count.
     *
     * @param  \Wafto\Sepomex\Models\Sepomex  $model
     * @param  \Wafto\Sepomex\Support\DelimitedFileIterator  $iterator
     * @param  int  $lines
     * @return int
     */
    protected function startImport($model, $iterator, $lines)
    {
        $this->comment(sprintf('Parsing [%s] rows from file...', $lines));

        $chunk = intval($this->option('chunk'));
        $bar = $this->output->createProgressBar($lines);
        $accumulator = [];
        $inserted = 0;

        foreach ($iterator as $row) {
            $accumulator[] = $row;

            // Insert when chunk size is reached
            if (count($accumulator) >= $chunk) {
                $count = count($accumulator);
                $model->insert($accumulator);
                $bar->advance($count);
                $inserted += $count;
                $accumulator = [];
            }
        }

        // Insert remaining rows
        if (count($accumulator) > 0) {
            $count = count($accumulator);
            $model->insert($accumulator);
            $bar->advance($count);
            $inserted += $count;
        }

        $bar->finish();

        return $inserted;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(Sepomex $model, CachedRepository $cache)
    {
        try {
            $filePath = config('sepomex.source_file');
            $inputEncoding = config('sepomex.encoding_input');
            $outputEncoding = config('sepomex.encoding_output');

            // Create iterator, skipping first line (usage & restrictions)
            $iterator = new DelimitedFileIterator(
                $filePath,
                '|',
                $inputEncoding,
                $outputEncoding,
                1
            );

            // Read headers from second line
            $iterator->readHeaders();

            // Count lines (excluding header and first line)
            $lines = $iterator->countLines() - 1;

            // Clear and import atomically so a failure keeps the existing data.
            $inserted = $model->getConnection()->transaction(function () use ($model, $iterator, $lines) {
                $this->clearTable($model);

                return $this->startImport($model, $iterator, $lines);
            });

            $info = sprintf("Inserted [%s] rows from [%s] file lines in %s table.\n", $inserted, $lines, $model->getTable());

            $this->info($info);

            $cache->flush();
            $this->info('Cache cleared.');
        } catch (\Throwable $e) {
            $this->error($e->getMessage());

            return 1;
        }
    }
}
