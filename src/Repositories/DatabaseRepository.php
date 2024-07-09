<?php

namespace Wafto\Sepomex\Repositories;

use Wafto\Sepomex\Contracts\SepomexContract;
use Wafto\Sepomex\Entities\State;
use Wafto\Sepomex\Models\Sepomex;

/**
 * Class DatabaseRepository.
 */
class DatabaseRepository implements SepomexContract
{
    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
