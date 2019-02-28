<?php

namespace Aftab\Sepomex\Tests;

use Illuminate\Support\Facades\Artisan;

/**
 * Class ImporterCommandTest.
 */
class ImporterCommandTest extends TestCase
{
    /** @test */
    public function it_should_run_the_command()
    {
        $this
            ->artisan('sepomex:import', [
                '--chunk' => '50',
            ])
            ->expectsOutput('Truncating table...')
            ->expectsOutput('Table truncated.')
            ->expectsOutput('Parsing [404] rows from file...')
            ->expectsOutput('Inserted [403] rows from [404] file lines in sepomex table.')
            ->assertExitCode(0);
    }

    /** @test */
    public function it_should_run_throw_exception_with_no_source_file()
    {
        config(['sepomex.source_file' => 'foo.txt']);

        $this
            ->artisan('sepomex:import', [
                '--chunk' => '50',
            ])
            ->expectsOutput('No source file found on foo.txt, please make sure to download it.')
            ->assertExitCode(0);
    }
}
