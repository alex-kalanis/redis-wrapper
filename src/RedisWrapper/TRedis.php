<?php

namespace kalanis\RedisWrapper\RedisWrapper;


use Redis;


/**
 * Trait TRedis
 * @package kalanis\RedisWrapper\RedisWrapper
 * Trait for add Redis extension connector into the classes
 */
trait TRedis
{
    protected Redis $redis;

    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * @param int|null $iterator
     * @param string $mask
     * @return string[]
     */
    protected function scan(&$iterator, string $mask): array
    {
        $keys = $this->redis->scan($iterator, $mask);
        return false === $keys ? [] : array_map('strval', $keys);
    }

    protected function get(string $key): ?string
    {
        $data = $this->redis->get($key);
        return false !== $data ? strval($data) : null;
    }

    protected function append(string $key, string $value): int
    {
        return $this->redis->append($key, $value);
    }

    protected function del(string $key): bool
    {
        return boolval($this->redis->del($key));
    }
}
