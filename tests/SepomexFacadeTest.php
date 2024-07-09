<?php

namespace Wafto\Sepomex\Tests;

use Wafto\Sepomex\Facades\Sepomex;

/**
 * Class SepomexFacadeTest.
 */
class SepomexFacadeTest extends TestCase
{
    /** @test */
    public function facade_should_get_by_postal_code()
    {
        $this->artisan('sepomex:import', [
            '--chunk' => '50',
        ]);

        $arr = Sepomex::getByPostal('11590');

        $this->assertArrayHasKey('postal', $arr[0]);
        $this->assertEquals('11590', $arr[0]['postal']);
    }

    /** @test */
    public function facade_should_get_states_array()
    {
        $this->artisan('sepomex:import', [
            '--chunk' => '50',
        ]);

        $states = Sepomex::getStates();

        $this->assertCount(3, $states);
    }
}
