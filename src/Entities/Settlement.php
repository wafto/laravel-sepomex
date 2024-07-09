<?php

namespace Wafto\Sepomex\Entities;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Class Settlement.
 */
class Settlement implements Arrayable
{
    /**
     * @var string
     */
    protected $postal;

    /**
     * @var State
     */
    protected $state;

    /**
     * @var City
     */
    protected $city;

    /**
     * @var District
     */
    protected $district;

    /**
     * @var Location
     */
    protected $location;

    /**
     * @return string
     */
    public function getPostal(): string
    {
        return $this->postal;
    }

    /**
     * @param string $postal
     *
     * @return void
     */
    public function setPostal(string $postal): void
    {
        $this->postal = $postal;
    }

    /**
     * @return State
     */
    public function getState(): State
    {
        return $this->state;
    }

    /**
     * @param State $state
     *
     * @return void
     */
    public function setState(State $state): void
    {
        $this->state = $state;
    }

    /**
     * @return City
     */
    public function getCity(): City
    {
        return $this->city;
    }

    /**
     * @param City $city
     *
     * @return void
     */
    public function setCity(City $city): void
    {
        $this->city = $city;
    }

    /**
     * @return District
     */
    public function getDistrict(): District
    {
        return $this->district;
    }

    /**
     * @param District $district
     *
     * @return void
     */
    public function setDistrict(District $district): void
    {
        $this->district = $district;
    }

    /**
     * @return Location
     */
    public function getLocation(): Location
    {
        return $this->location;
    }

    /**
     * @param Location $location
     *
     * @return void
     */
    public function setLocation(Location $location): void
    {
        $this->location = $location;
    }

    /**
     * {@inheritdoc}
     * @return array
     */
    public function toArray(): array
    {
        return [
            'postal' => $this->getPostal(),
            'state' => optional($this->state)->toArray(),
            'city' => optional($this->city)->toArray(),
            'district' => optional($this->district)->toArray(),
            'location' => optional($this->location)->toArray(),
        ];
    }
}
