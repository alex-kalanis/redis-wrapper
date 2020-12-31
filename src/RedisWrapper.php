<?php

namespace kalanis\RedisWrapper;


use Redis;


/**
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
    /** @var Redis|null */
    protected static $redis = null;

    public static function setRedis(Redis $redis): void
    {
        static::$redis = $redis;
    }

    public static function register()
    {
        if (in_array("redis", stream_get_wrappers())) {
            stream_wrapper_unregister("redis");
        }
        stream_wrapper_register("redis", "\RedisWrapper\RedisWrapper");
    }

    public function __construct()
    {
        $this->keyQuery = new RedisWrapper\Keys(static::$redis);
        $this->fileQuery = new RedisWrapper\Data(static::$redis);
    }
}
