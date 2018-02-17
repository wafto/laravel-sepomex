<?php

namespace Aftab\Sepomex\Models;

use Aftab\Sepomex\Entities\City;
use Aftab\Sepomex\Entities\State;
use Aftab\Sepomex\Entities\District;
use Aftab\Sepomex\Entities\Location;
use Aftab\Sepomex\Entities\Settlement;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Sepomex.
 */
class Sepomex extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'c_estado' => 'integer',
        'c_oficina' => 'integer',
        'c_CP' => 'integer',
        'c_tipo_asenta' => 'integer',
        'c_mnpio' => 'integer',
        'c_cve_ciudad' => 'integer',
    ];

    /**
     * {@inheritdoc}
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->table = config('sepomex.table_name');
        parent::__construct($attributes);
    }

    /**
     * Get the data from the specified postal code.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string                                $code
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePostalCode($query, $code)
    {
        return $query->where('d_codigo', $code);
    }

    /**
     * Gets a entity representation of the model.
     *
     * @return Settlement
     */
    public function toEntity()
    {
        $settlement = new Settlement();
        $settlement->setPostal(array_get($this->attributes, 'd_codigo'));

        if ($this->hasAttributes(['c_estado', 'd_estado'])) {
            $settlement->setState(new State($this->attributes['c_estado'], $this->attributes['d_estado']));
        }

        if ($this->hasAttributes(['c_cve_ciudad', 'd_ciudad'])) {
            $settlement->setCity(new City($this->attributes['c_cve_ciudad'], $this->attributes['d_ciudad']));
        }

        if ($this->hasAttributes(['c_mnpio', 'D_mnpio'])) {
            $settlement->setDistrict(new District($this->attributes['c_mnpio'], $this->attributes['D_mnpio']));
        }

        if ($this->hasAttributes(['d_tipo_asenta', 'd_asenta'])) {
            $settlement->setLocation(new Location($this->attributes['d_tipo_asenta'], $this->attributes['d_asenta']));
        }

        return $settlement;
    }

    /**
     * Validates the existance of some model attributes.
     *
     * @param array $attributes
     * @return bool
     */
    protected function hasAttributes(array $attributes)
    {
        foreach ($attributes as $attr) {
            if (! array_has($this->attributes, $attr)) {
                return false;
            }
        }

        return true;
    }
}
