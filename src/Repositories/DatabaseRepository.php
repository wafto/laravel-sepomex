<?php

namespace Aftab\Sepomex\Repositories;

use Aftab\Sepomex\Entities\State;
use Aftab\Sepomex\Models\Sepomex;
use Aftab\Sepomex\Contracts\SepomexContract;

/**
 * Class DatabaseRepository
 * @package Aftab\Sepomex
 */
class DatabaseRepository implements SepomexContract
{
    /**
     * @inheritdoc
     *
     * @param string $postal
     * @return array
     */
    public function getByPostal(string $postal): array
    {
        return Sepomex::postalCode($postal)
            ->get()
            ->map(function (Sepomex $item) {
                return $item->toEntity();
            })
            ->toArray();
    }

    /**
     * @inheritdoc
     *
     * @return array
     */
    public function getStates(): array
    {
        return Sepomex::select(['c_estado', 'd_estado'])
            ->distinct()
            ->get()
            ->map(function ($row) {
                return new State($row->c_estado, $row->d_estado);
            })
            ->toArray();
    }
}
