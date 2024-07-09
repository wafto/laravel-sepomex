<?php

namespace Wafto\Sepomex\Repositories;

use Wafto\Sepomex\Contracts\SepomexContract;
use Illuminate\Contracts\Cache\Repository;

/**
 * Class CachedRepository.
 */
class CachedRepository implements SepomexContract
{
    /**
     * @var SepomexContract
     */
    protected $next;

    /**
     * @var Repository
     */
    protected $cache;

    /**
     * CachedRepository constructor.
     *
     * @param SepomexContract $next
     * @param Repository $cache
     */
    public function __construct(SepomexContract $next, Repository $cache)
    {
        $this->next = $next;
        $this->cache = $cache;
    }

    /**
     * {@inheritdoc}
     *
     * @param string $postal
     * @return array
     */
    public function getByPostal(string $postal): array
    {
        $hash = sprintf('%s.%s', config('sepomex.table_name'), $postal);

        return $this->cache->rememberForever($hash, function () use ($postal) {
            return $this->next->getByPostal($postal);
        });
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getStates(): array
    {
        $hash = sprintf('%s.states', config('sepomex.table_name'));

        return $this->cache->rememberForever($hash, function () {
            return $this->next->getStates();
        });
    }
}
