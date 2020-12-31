<?php

namespace kalanis\RedisWrapper\RedisWrapper;

use Redis;

/**
 * Trait for add Redis extension connector into the classes
 */
trait TRedis
{
    protected $redis = null;

    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    protected function scan(&$iterator, string $mask)
    {
        return $this->redis->scan($iterator, $mask);
    }

    protected function get(string $key): string
    {
        return (string)$this->redis->get($key);
    }

    protected function append(string $key, string $value): int
    {
        return $this->redis->append($key, $value);
    }

    protected function del(string $key): bool
    {
        return (bool)$this->redis->del($key);
    }
}
