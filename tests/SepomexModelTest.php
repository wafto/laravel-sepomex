<?php

namespace Aftab\Sepomex\Tests;

use Aftab\Sepomex\Models\Sepomex;
use Aftab\Sepomex\Entities\Settlement;

/**
 * Class SepomexModelTest
 * @package Aftab\Sepomex\Tests
 */
class SepomexModelTest extends TestCase
{
    /** @test */
    public function it_should_connect_to_proper_table()
    {
        $model = new Sepomex();

        $this->assertEquals(config('sepomex.table_name'), $model->getTable());
    }

    /** @test */
    public function it_should_get_by_postal_code()
    {
        $this->artisan('sepomex:import', [
            '--chunk' => '50'
        ]);

        $model = Sepomex::postalCode('11590')->first();

        $this->assertNotNull($model);

        $collection = Sepomex::postalCode('67200')->get();

        $this->assertEquals(6, $collection->count());

        $this->assertInstanceOf(Settlement::class, $model->toEntity());
    }
}
