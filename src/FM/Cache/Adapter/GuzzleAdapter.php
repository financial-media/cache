<?php

namespace FM\Cache\Adapter;

use FM\Cache\CacheInterface;
use Guzzle\Cache\CacheAdapterInterface;

/**
 * @deprecated The financial-media/cache library is deprecated. Use treehouselabs/cache instead
 */
class GuzzleAdapter implements CacheAdapterInterface
{
    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * @param CacheInterface $cache
     */
    public function __construct(CacheInterface $cache)
    {
        trigger_error('The financial-media/cache library is deprecated. Use treehouselabs/cache instead', E_USER_DEPRECATED);

        $this->cache = $cache;
    }

    /**
     * @inheritdoc
     */
    public function contains($id, array $options = null)
    {
        return $this->cache->has($id);
    }

    /**
     * @inheritdoc
     */
    public function delete($id, array $options = null)
    {
        return $this->cache->remove($id);
    }

    /**
     * @inheritdoc
     */
    public function fetch($id, array $options = null)
    {
        return $this->cache->get($id);
    }

    /**
     * @inheritdoc
     */
    public function save($id, $data, $lifeTime = false, array $options = null)
    {
        return $this->cache->set($id, $data, $lifeTime);
    }
}
