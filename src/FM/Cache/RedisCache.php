<?php

namespace FM\Cache;

use Redis;

class RedisCache implements CacheInterface
{
    /**
     * @var Redis
     */
    protected $redis;

    /**
     * Extra in-memory cache to prevent double calls to Redis
     */
    protected $localCache = array();

    /**
     * @param Redis $redis
     */
    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * @inheritdoc
     */
    public function has($key)
    {
        return array_key_exists($key, $this->localCache) || $this->redis->exists($key);
    }

    /**
     * @inheritdoc
     */
    public function get($key)
    {
        if (!array_key_exists($key, $this->localCache)) {
            $this->localCache[$key] = json_decode($this->redis->get($key), true);
        }

        return $this->localCache[$key];
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value, $ttl = 0)
    {
        $res = $this->redis->set($key, json_encode($value));
        if ($ttl > 0) {
            $this->redis->expire($key, $ttl);
        }

        $this->localCache[$key] = $value;

        return $res;
    }

    /**
     * @inheritdoc
     */
    public function remove($key)
    {
        $res = $this->redis->del($key);

        if ($res && array_key_exists($key, $this->localCache)) {
            unset($this->localCache[$key]);
        }

        return (boolean) $res;
    }

    public function clear()
    {
        $res = $this->redis->flushDB();

        if ($res) {
            $this->localCache = array();
        }

        return $res;
    }

    public function appendToList($list, $value)
    {
        return $this->redis->sAdd($list, $value);
    }

    public function getListItems($list)
    {
        return $this->redis->sMembers($list);
    }

    public function removeFromList($list, $value)
    {
        return $this->redis->sRem($list, $value);
    }
}
