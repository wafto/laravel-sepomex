<?php

namespace Aftab\Sepomex\Entities;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Class Location
 * @package Aftab\Sepomex\Entities
 */
class Location implements Arrayable
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $name;

    /**
     * @return string
     */
    public function getType() : string
    {
        return $this->id;
    }

    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @param string $name
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
            'type' => $this->getType(),
            'name' => $this->getName(),
        ];
    }
}
