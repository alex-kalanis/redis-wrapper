<?php

namespace kalanis\RedisWrapper;


use kalanis\RedisWrapper\Shared\RedisException;
use Redis;


/**
 * Class RedisWrapper
 * @package kalanis\RedisWrapper
 * Wrapper to plug Redis into PHP - Redis extension
 *
 * Usage:
 * - In initialization:
RedisWrapper::setRedis($redis);
RedisWrapper::register();
 * - somewhere in code:
file_get_contents('redis://any/key/in/redis/storage');
file_put_contents('redis://another/key/in/storage', 'add something');
 */
class RedisWrapper extends Shared\AWrapper
{
    protected static ?Redis $redis = null;

    public static function setRedis(Redis $redis): void
    {
        static::$redis = $redis;
    }

    public static function register(): void
    {
        if (in_array("redis", stream_get_wrappers())) {
            stream_wrapper_unregister("redis");
        }
        stream_wrapper_register("redis", "\kalanis\RedisWrapper\RedisWrapper");
    }

    /**
     * @throws RedisException
     */
    public function __construct()
    {
        $this->keyQuery = new RedisWrapper\Keys($this->getClient());
        $this->fileQuery = new RedisWrapper\Data($this->getClient());
    }

    /**
     * @throws RedisException
     * @return Redis
     */
    protected function getClient(): Redis
    {
        if (empty(static::$redis)) {
            throw new RedisException('Set the client first!');
        }
        return static::$redis;
    }
}
