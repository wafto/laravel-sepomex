<?php

namespace Wafto\Sepomex\Entities;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Class Location.
 */
class Location implements Arrayable
{
    protected string $type;

    protected string $name;

    public function __construct(string $type, string $name)
    {
        $this->type = $type;
        $this->name = $name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        return [
            'type' => $this->getType(),
            'name' => $this->getName(),
        ];
    }
}
