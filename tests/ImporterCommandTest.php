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
        $this->artisan('sepomex:import', [
            '--chunk' => '50',
        ]);

        $output = Artisan::output();

        $this->assertContains('Truncating table...', $output);
        $this->assertContains('Table truncated.', $output);
        $this->assertContains('Parsing [404] rows from file...', $output);
        $this->assertContains('Inserted [403] rows from [404] file lines in sepomex table', $output);
    }

    /** @test */
    public function it_should_run_throw_exception_with_no_source_file()
    {
        config(['sepomex.source_file' => 'foo.txt']);

        $this->artisan('sepomex:import', [
            '--chunk' => '50',
        ]);

        $output = Artisan::output();

        $this->assertContains("No source file found on foo.txt, please make sure to download it.\n", $output);
    }
}
