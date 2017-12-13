<?php

namespace Aftab\Sepomex\Entities;

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
     */
    public function setPostal(string $postal)
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
     */
    public function setState(State $state)
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
     */
    public function setCity(City $city)
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
     */
    public function setDistrict(District $district)
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
     */
    public function setLocation(Location $location)
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
            'state' => $this->state ? $this->getState()->toArray() : null,
            'city' => $this->city ? $this->getCity()->toArray() : null,
            'district' => $this->district ? $this->getDistrict()->toArray() : null,
            'location' => $this->location ? $this->getLocation()->toArray() : null,
        ];
    }
}
