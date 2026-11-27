<?php

namespace Wafto\Sepomex\Repositories;

use Closure;
use Illuminate\Contracts\Cache\Repository;
use Wafto\Sepomex\Contracts\SepomexContract;

class CachedRepository implements SepomexContract
{
    public function __construct(
        protected SepomexContract $next,
        protected Repository $cache,
        protected ?int $ttl = null,
    ) {}

    public function getByPostal(string $postal): array
    {
        return $this->remember("postal.{$postal}", fn () => $this->next->getByPostal($postal));
    }

    public function getStates(): array
    {
        return $this->remember('states', fn () => $this->next->getStates());
    }

    /**
     * Invalidate all cached entries by bumping the key version.
     *
     * Works on every cache driver, unlike tags which are only
     * supported by taggable stores (redis, memcached, array).
     */
    public function flush(): void
    {
        $this->cache->forever($this->versionKey(), $this->version() + 1);
    }

    protected function remember(string $key, Closure $callback): array
    {
        $key = sprintf('%s.v%d.%s', $this->prefix(), $this->version(), $key);

        return $this->cache->remember($key, $this->ttl, $callback);
    }

    protected function version(): int
    {
        return (int) $this->cache->rememberForever($this->versionKey(), fn () => 1);
    }

    protected function versionKey(): string
    {
        return "{$this->prefix()}.version";
    }

    protected function prefix(): string
    {
        return 'sepomex.'.config('sepomex.table_name');
    }
}
