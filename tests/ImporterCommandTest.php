<?php

it('runs the import command successfully', function () {
    $this->artisan('sepomex:import', ['--chunk' => '50'])
        ->expectsOutput('Truncating table...')
        ->expectsOutput('Table truncated.')
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
