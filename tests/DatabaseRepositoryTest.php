<?php

namespace Aftab\Sepomex\Tests;

use Aftab\Sepomex\Repositories\DatabaseRepository;

/**
 * Class DatabaseRepositoryTest
 * @package Aftab\Sepomex\Tests
 */
class DatabaseRepositoryTest extends TestCase
{
    /** @test */
    public function get_by_postal_code_array()
    {
        $this->artisan('sepomex:import', [
            '--chunk' => '50'
        ]);

        $repo = new DatabaseRepository();
        $arr = $repo->getByPostal('11590');

        $this->assertArrayHasKey('postal', $arr[0]);
        $this->assertEquals('11590', $arr[0]['postal']);
    }

    /** @test */
    public function get_states_array()
    {
        $this->artisan('sepomex:import', [
            '--chunk' => '50'
        ]);

        $repo = new DatabaseRepository();
        $states = $repo->getStates();

        $this->assertCount(3, $states);
    }
}
