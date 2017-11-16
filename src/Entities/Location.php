<?php

namespace Aftab\Sepomex\Entities;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Class Location.
 */
class Location implements Arrayable
{
    /**
     * Location constructor.
     *
     * @param string $type
     * @param string $name
     */
    public function __construct(string $type, string $name)
    {
        $this->setType($type);
        $this->setName($name);
    }

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
        return $this->type;
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
     * {@inheritdoc}
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
