<?php

namespace FM\Cache;

use Memcached;

/**
 * @deprecated The financial-media/cache library is deprecated. Use treehouselabs/cache instead
 */
class MemcachedCache implements CacheInterface
{
    /**
     * @var Memcached
     */
    protected $memcached;

    /**
     * Extra in-memory cache to prevent double calls to Redis
     */
    protected $localCache = array();

    /**
     * @param Memcached $memcached
     */
    public function __construct(Memcached $memcached)
    {
        trigger_error('The financial-media/cache library is deprecated. Use treehouselabs/cache instead', E_USER_DEPRECATED);

        $this->memcached = $memcached;
    }

    /**
     * @inheritdoc
     */
    public function has($key)
    {
        return array_key_exists($key, $this->localCache) || (false !== $this->memcached->get($key));
    }

    /**
     * @inheritdoc
     */
    public function get($key)
    {
        if (!array_key_exists($key, $this->localCache)) {
            $this->localCache[$key] = $this->memcached->get($key);
        }

        return $this->localCache[$key];
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value, $ttl = 0)
    {
        /*
         * If the ttl is more than 30 days, Memcache assumes it's a Unix time instead of
         * an offset from current time. We add the current time to ensure the right time is used.
         *
         * @see http://nl3.php.net/manual/en/memcached.expiration.php
         */
        if ($ttl > 60 * 60 * 24 * 30) {
            $ttl = time() + $ttl;
        }

        $res = $this->memcached->set($key, $value, $ttl);
        $this->localCache[$key] = $value;

        return $res;
    }

    /**
     * @inheritdoc
     */
    public function remove($key)
    {
        $res = $this->memcached->delete($key);

        if ($res && array_key_exists($key, $this->localCache)) {
            unset($this->localCache[$key]);
        }

        return (boolean) $res;
    }
}
