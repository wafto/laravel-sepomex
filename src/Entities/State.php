<?php

namespace Aftab\Sepomex\Entities;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Class State
 *
 * @package Aftab\Sepomex\Entities
 */
class State implements Arrayable
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @return mixed
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function toArray() : array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
        ];
    }
}
