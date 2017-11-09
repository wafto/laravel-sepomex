<?php

namespace Aftab\Sepomex\Tests;

use Aftab\Sepomex\Models\Sepomex;

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
}
