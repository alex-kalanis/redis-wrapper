<?php

namespace RedisWrapper;


use kalanis\kw_storage\Interfaces\IStorage;
use RedisWrapper\PredisWrapper\TPredis;
use Traversable;


class PredisStorage implements IStorage
{
    use TPredis;

    /** @var int */
    protected $timeout = 0;

    public function check(string $key): bool
    {
        return $this->redis->isConnected();
    }

    public function exists(string $key): bool
    {
        // cannot call exists() - get on non-existing key returns false
        return (false !== $this->redis->get($key));
    }

    public function load(string $key): string
    {
        return $this->get($key);
    }

    public function save(string $key, $data, ?int $timeout = null): bool
    {
        if (is_null($timeout)) {
            return (false !== $this->redis->set($key, $data));
        } else {
            return (false !== $this->redis->set($key, $data, 'EX', $timeout));
        }
    }

    public function remove(string $key): bool
    {
        return $this->del($key);
    }

    public function lookup(string $key): Traversable
    {
        $iterator = NULL; // initialize iterator
        while ($arr_keys = $this->redis->scan($iterator, [
            'match' => $key . '*',
        ])) {
            foreach ($arr_keys as $str_key) {
                if (!empty($str_key)) {
                    yield $str_key;
                }
            }
        }
    }

    public function increment(string $key): bool
    {
        return (false !== $this->redis->incr($key));
    }

    public function decrement(string $key): bool
    {
        return (false !== $this->redis->decr($key));
    }

    public function removeMulti(array $keys): array
    {
        $result = [];
        foreach ($keys as $index => $key) {
            $result[$index] = $this->remove($key);
        }
        return $result;
    }
}
