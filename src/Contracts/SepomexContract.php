<?php

namespace Wafto\Sepomex\Contracts;

/**
 * Interface SepomexContract.
 */
interface SepomexContract
{
    /**
     * Get an array of settlements that correspond to the postal code.
     *
     * @param string $postal
     * @return array
     */
    public function getByPostal(string $postal): array;

    /**
     * Get full list of available states.
     *
     * @return array
     */
    public function getStates(): array;
}
