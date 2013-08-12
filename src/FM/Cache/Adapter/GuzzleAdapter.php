<?php

namespace FM\Cache\Adapter;

use Guzzle\Cache\CacheAdapterInterface;
use FM\Cache\CacheInterface;

class GuzzleAdapter implements CacheAdapterInterface
{
    protected $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function contains($id, array $options = null)
    {
        return $this->cache->has($id);
    }

    public function delete($id, array $options = null)
    {
        return $this->cache->remove($id);
    }

    public function fetch($id, array $options = null)
    {
        return $this->cache->get($id);
    }

    public function save($id, $data, $lifeTime = false, array $options = null)
    {
        return $this->cache->set($id, $data, $lifeTime);
    }
}
