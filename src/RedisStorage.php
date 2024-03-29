<?php

namespace RedisWrapper;


use kalanis\kw_storage\Interfaces\ITarget;
use kalanis\RedisWrapper\RedisWrapper\TRedis;
use Traversable;


/**
 * Class RedisStorage
 * @package RedisWrapper
 * Storing content in Redis where there is Storage interface
 */
class RedisStorage implements ITarget
{
    use TRedis;

    protected int $timeout = 0;

    public function check(string $key): bool
    {
        return 'PONG' == strval($this->redis->ping());
    }

    public function exists(string $key): bool
    {
        // cannot call exists() - get on non-existing key returns false
        return (false !== $this->redis->get($key));
    }

    public function load(string $key): string
    {
        return strval($this->get($key));
    }

    public function save(string $key, string $data, ?int $timeout = null): bool
    {
        if (is_null($timeout)) {
            return (false !== $this->redis->set($key, $data));
        } else {
            return (false !== $this->redis->set($key, $data, $timeout));
        }
    }

    public function remove(string $key): bool
    {
        return $this->del($key);
    }

    /**
     * @param string $key
     * @return Traversable<string>
     */
    public function lookup(string $key): Traversable
    {
        $iterator = NULL; // initialize iterator
        while ($arr_keys = $this->redis->scan($iterator, $key . '*')) {
            foreach ($arr_keys as $str_key) {
                if (!empty($str_key)) {
                    yield $str_key;
                }
            }
        }
    }

    public function increment(string $key): bool
    {
        return boolval($this->redis->incr($key));
    }

    public function decrement(string $key): bool
    {
        return boolval($this->redis->decr($key));
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
