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
        $settlement->setPostal($this->d_codigo);

        if (! empty($this->c_estado) && ! empty($this->d_estado)) {
            $settlement->setState(new State($this->c_estado, $this->d_estado));
        }

        if (! empty($this->c_cve_ciudad) && ! empty($this->d_ciudad)) {
            $settlement->setCity(new City($this->c_cve_ciudad, $this->d_ciudad));
        }

        if (! empty($this->c_mnpio) && ! empty($this->D_mnpio)) {
            $settlement->setDistrict(new District($this->c_mnpio, $this->D_mnpio));
        }

        if (! empty($this->d_tipo_asenta) && ! empty($this->d_asenta)) {
            $settlement->setLocation(new Location($this->d_tipo_asenta, $this->d_asenta));
        }

        return $settlement;
    }
}
