<?php

namespace Wafto\Sepomex\Tests;

use Wafto\Sepomex\Entities\State;

/**
 * Class StateEntityTest.
 */
class StateEntityTest extends TestCase
{
    /** @test */
    public function entity_state_setters_and_getters()
    {
        $state = new State(9, 'Ciudad de México');

        $this->assertEquals(9, $state->getId());
        $this->assertEquals('Ciudad de México', $state->getName());
    }

    /** @test */
    public function entity_state_to_array()
    {
        $state = new State(9, 'Ciudad de México');

        $this->assertArrayHasKey('id', $state->toArray());
        $this->assertArrayHasKey('name', $state->toArray());
    }
}
