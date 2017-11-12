<?php

namespace Aftab\Sepomex\Tests;

use Aftab\Sepomex\Entities\District;

/**
 * Class DistrictEntityTest
 * @package Aftab\Sepomex\Tests
 */
class DistrictEntityTest extends TestCase
{
    /** @test */
    public function entity_district_setters_and_getters()
    {
        $district = new District(16, 'Miguel Hidalgo');

        $this->assertEquals(16, $district->getId());
        $this->assertEquals('Miguel Hidalgo', $district->getName());
    }

    /** @test */
    public function entity_district_to_array()
    {
        $district = new District(16, 'Miguel Hidalgo');

        $this->assertArrayHasKey('id', $district->toArray());
        $this->assertArrayHasKey('name', $district->toArray());
    }
}
