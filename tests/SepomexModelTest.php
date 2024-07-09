<?php

namespace Wafto\Sepomex\Tests;

use Wafto\Sepomex\Entities\Settlement;
use Wafto\Sepomex\Models\Sepomex;

/**
 * Class SepomexModelTest.
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
            '--chunk' => '50',
        ]);

        $model = Sepomex::postalCode('11590')->first();

        $this->assertNotNull($model);

        $collection = Sepomex::postalCode('67200')->get();

        $this->assertEquals(6, $collection->count());

        $this->assertInstanceOf(Settlement::class, $model->toEntity());
    }
}
