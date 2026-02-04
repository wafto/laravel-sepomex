<?php

namespace Wafto\Sepomex\Entities;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Class Settlement.
 */
class Settlement implements Arrayable
{
    protected string $postal;

    protected ?State $state = null;

    protected ?City $city = null;

    protected ?District $district = null;

    protected ?Location $location = null;

    public function getPostal(): string
    {
        return $this->postal;
    }

    public function setPostal(string $postal): void
    {
        $this->postal = $postal;
    }

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(State $state): void
    {
        $this->state = $state;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(City $city): void
    {
        $this->city = $city;
    }

    public function getDistrict(): ?District
    {
        return $this->district;
    }

    public function setDistrict(District $district): void
    {
        $this->district = $district;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(Location $location): void
    {
        $this->location = $location;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        return [
            'postal' => $this->getPostal(),
            'state' => $this->state?->toArray(),
            'city' => $this->city?->toArray(),
            'district' => $this->district?->toArray(),
            'location' => $this->location?->toArray(),
        ];
    }
}
