<?php

namespace RedisWrapper;

use Predis;

/**
 * Wrapper to plug Redis into PHP - Predis cluster library
 *
 * Usage:
 * - In initialization:
PredisWrapper::setRedisClient($redis);
PredisWrapper::register();
 * - somewhere in code:
file_get_contents('redis://any/key/in/redis/storage');
file_put_contents('redis://another/key/in/storage', 'add something');
 */
class PredisWrapper extends Shared\AWrapper
{
    /** @var Predis\Client|null */
    protected static $redis = null;

    public static function setRedisClient(Predis\Client $redis): void
    {
        static::$redis = $redis;
    }

    public static function register()
    {
        if (in_array("redis", stream_get_wrappers())) {
            stream_wrapper_unregister("redis");
        }
        stream_wrapper_register("redis", "\RedisWrapper\PredisWrapper");
    }

    public function __construct()
    {
        $this->keyQuery = new PredisWrapper\Keys(static::$redis);
        $this->fileQuery = new PredisWrapper\Data(static::$redis);
    }
}
