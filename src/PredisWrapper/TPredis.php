<?php

namespace kalanis\RedisWrapper\PredisWrapper;


use Predis;


/**
 * Trait TPredis
 * @package kalanis\RedisWrapper\PredisWrapper
 * Trait for add Predis library into the classes
 */
trait TPredis
{
    protected Predis\Client $redis;

    public function __construct(Predis\Client $redis)
    {
        $this->redis = $redis;
    }

    /**
     * @param mixed $iterator
     * @param string $mask
     * @return string[]
     */
    protected function scan(&$iterator, string $mask): array
    {
        $keys = $this->redis->scan($iterator, [
            'match' => $mask,
        ]);
        return array_map('strval', $keys);
    }

    protected function get(string $key): ?string
    {
        return $this->redis->get($key);
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
