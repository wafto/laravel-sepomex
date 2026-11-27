<?php

use Wafto\Sepomex\Models\Sepomex;

it('runs the import command successfully', function () {
    $this->artisan('sepomex:import', ['--chunk' => '50'])
        ->expectsOutput('Clearing table...')
        ->expectsOutput('Table cleared.')
        ->expectsOutput('Parsing [403] rows from file...')
        ->expectsOutput("Inserted [403] rows from [403] file lines in sepomex table.\n")
        ->expectsOutput('Cache cleared.')
        ->assertExitCode(0);
});

it('shows error when source file is missing', function () {
    config(['sepomex.source_file' => 'foo.txt']);

    $this->artisan('sepomex:import', ['--chunk' => '50'])
        ->expectsOutput('No source file found on foo.txt, please make sure to download it.')
        ->assertExitCode(1);
});

it('keeps existing data when the import fails', function () {
    $this->artisan('sepomex:import', ['--chunk' => '50'])->assertExitCode(0);
    $before = Sepomex::query()->count();

    app()->bind(Sepomex::class, fn () => new class extends Sepomex
    {
        protected int $calls = 0;

        public function insert(array $values): bool
        {
            if (++$this->calls === 3) {
                throw new RuntimeException('Simulated failure');
            }

            return $this->newQuery()->insert($values);
        }
    });

    $this->artisan('sepomex:import', ['--chunk' => '50'])
        ->expectsOutput('Simulated failure')
        ->doesntExpectOutput('Cache cleared.')
        ->assertExitCode(1);

    expect(Sepomex::query()->count())->toBe($before);
});
