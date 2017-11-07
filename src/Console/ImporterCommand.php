<?php

namespace Aftab\Sepomex\Console;

use SplFileObject;
use Illuminate\Console\Command;
use Aftab\Sepomex\SepomexRepository;

/**
 * Class ImporterCommand
 * @package Aftab\Sepomex\Console
 */
class ImporterCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sepomex:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports sepomex data from the txt file provided by "Correos de México".';

    /**
     * Chunk size.
     *
     * @var int
     */
    protected $chunkSize = 500;

    /**
     * Execute the console command.
     *
     * @param \Aftab\Sepomex\SepomexRepository $repository
     * @return void
     */
    public function handle(SepomexRepository $repository)
    {
        try {
            // Source file.
            $source = $this->getSource();

            $lines = $this->fileRowCount();

            // Ignore first line that contain some usages & restrictions.
            $source->fgets();

            // Get the columns fields names.
            $keys = $this->prepareRow($source->fgets());

            // Truncate database.
            $this->comment('Truncating table...');
            $repository->truncate();
            $this->info('Table truncated.');


            $keyCount = count($keys);
            $accumulator = [];
            $inserted = 0;
            $lines -= 2;

            // Starting import.
            $this->comment(sprintf('Importing [%s] rows from file...', $lines));

            $bar = $this->output->createProgressBar($lines);

            while ($source->valid()) {
                $data = $this->prepareRow($source->fgets());

                if (count($data) != $keyCount) {
                    continue;
                }

                $accumulator[] = array_combine($keys, $data);

                if (count($accumulator) >= $this->chunkSize) {
                    $repository->insertChunk($accumulator);
                    $accCount = count($accumulator);
                    $bar->advance($accCount);
                    $inserted += $accCount;
                    $accumulator = [];
                }
            }

            if (count($accumulator)) {
                $repository->insertChunk($accumulator);
                $accCount = count($accumulator);
                $bar->advance($accCount);
                $inserted += $accCount;
            }

            $bar->finish();

            $this->info(sprintf("\nInserted [%s] rows in %s table.", $inserted, $repository->table()));

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

        $handle = fopen($path, "r");
        while (!feof($handle)) {
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
     * @param string $str
     * @return array
     */
    protected function prepareRow($str)
    {
        $data = array_map('trim', explode('|', iconv('iso-8859-1', 'utf-8', $str)));
        foreach ($data as $key => $value) {
            if (empty($value)) {
                $data[$key] = null;
            }
        }
        return $data;
    }
}
