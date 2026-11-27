<?php

namespace Wafto\Sepomex\Contracts;

/**
 * Results are returned as plain arrays (the `toArray()` output of the
 * entities in `Wafto\Sepomex\Entities`), so they can be cached and
 * serialized to JSON directly.
 *
 * @phpstan-type IdNameArray array{id: int, name: string}
 * @phpstan-type LocationArray array{type: string, name: string}
 * @phpstan-type SettlementArray array{
 *     postal: string,
 *     state: IdNameArray|null,
 *     city: IdNameArray|null,
 *     district: IdNameArray|null,
 *     location: LocationArray|null,
 * }
 */
interface SepomexContract
{
    /**
     * Get the settlements that correspond to the postal code.
     *
     * Returns an empty array when the postal code does not exist.
     *
     * @return list<SettlementArray>
     */
    public function getByPostal(string $postal): array;

    /**
     * Get the full list of available states.
     *
     * @return list<IdNameArray>
     */
    public function getStates(): array;
}
