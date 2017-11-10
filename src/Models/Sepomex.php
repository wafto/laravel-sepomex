<?php

namespace Aftab\Sepomex\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Sepomex
 *
 * @package Aftab\Sepomex
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
     * @inheritdoc
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
}
