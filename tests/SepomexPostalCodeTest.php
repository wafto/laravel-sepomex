<?php

namespace Aftab\Sepomex\Tests;

use Aftab\Sepomex\Models\Sepomex;

/**
 * Class SepomexPostalCodeTest
 * @package Aftab\Sepomex\Tests
 */
class SepomexPostalCodeTest extends TestCase
{
    /** @test */
    public function it_should_get_by_postal_code()
    {
        $this->artisan('sepomex:import', [
            '--chunk' => '50'
        ]);

        $this->assertNotNull(Sepomex::postalCode('11590')->first());

        $collection = Sepomex::postalCode('67200')->get();

        $this->assertEquals(6, $collection->count());
    }
}
