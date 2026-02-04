<?php

namespace Wafto\Sepomex\Repositories;

use Illuminate\Contracts\Cache\Repository;
use Wafto\Sepomex\Contracts\SepomexContract;

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
     */
    public function __construct(SepomexContract $next, Repository $cache)
    {
        $this->next = $next;
        $this->cache = $cache;
    }

    /**
     * {@inheritdoc}
     */
    public function getByPostal(string $postal): array
    {
        $hash = sprintf('%s.%s', config('sepomex.table_name'), $postal);

        return $this->cache->tags(['sepomex'])->rememberForever($hash, function () use ($postal) {
            return $this->next->getByPostal($postal);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getStates(): array
    {
        $hash = sprintf('%s.states', config('sepomex.table_name'));

        return $this->cache->tags(['sepomex'])->rememberForever($hash, function () {
            return $this->next->getStates();
        });
    }
}
