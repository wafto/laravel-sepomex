<?php

namespace Aftab\Sepomex\Tests;

use Illuminate\Contracts\Cache\Repository;
use Aftab\Sepomex\Repositories\CachedRepository;
use Aftab\Sepomex\Repositories\DatabaseRepository;

/**
 * Class CachedRepositoryTest.
 */
class CachedRepositoryTest extends TestCase
{
    /** @test */
    public function get_by_postal_code_array()
    {
        $this->artisan('sepomex:import', [
            '--chunk' => '50',
        ]);

        $repo = new CachedRepository(new DatabaseRepository(), app(Repository::class));
        $arr = $repo->getByPostal('11590');

        $this->assertArrayHasKey('postal', $arr[0]);
        $this->assertEquals('11590', $arr[0]['postal']);
    }

    /** @test */
    public function get_states_array()
    {
        $this->artisan('sepomex:import', [
            '--chunk' => '50',
        ]);

        $repo = $repo = new CachedRepository(new DatabaseRepository(), app(Repository::class));
        $states = $repo->getStates();

        $this->assertCount(3, $states);
    }
}
