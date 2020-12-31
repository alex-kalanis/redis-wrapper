<?php

namespace kalanis\RedisWrapper\PredisWrapper;


use Predis;


/**
 * Trait for add Predis library into the classes
 */
trait TPredis
{
    protected $redis = null;

    public function __construct(Predis\Client $redis)
    {
        $this->redis = $redis;
    }

    protected function scan(&$iterator, string $mask)
    {
        return $this->redis->scan($iterator, [
            'match' => $mask,
        ]);
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
