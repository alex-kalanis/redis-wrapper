<?php

namespace kalanis\RedisWrapper;


use kalanis\RedisWrapper\Shared\RedisException;
use Predis;


/**
 * Class PredisWrapper
 * @package kalanis\RedisWrapper
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
    protected static ?Predis\Client $redis = null;

    public static function setRedisClient(Predis\Client $redis): void
    {
        static::$redis = $redis;
    }

    public static function register(): void
    {
        if (in_array("redis", stream_get_wrappers())) {
            stream_wrapper_unregister("redis");
        }
        stream_wrapper_register("redis", "\kalanis\RedisWrapper\PredisWrapper");
    }

    /**
     * @throws RedisException
     */
    public function __construct()
    {
        $this->keyQuery = new PredisWrapper\Keys($this->getClient());
        $this->fileQuery = new PredisWrapper\Data($this->getClient());
    }

    /**
     * @throws RedisException
     * @return Predis\Client
     */
    protected function getClient(): Predis\Client
    {
        if (empty(static::$redis)) {
            throw new RedisException('Set the client first!');
        }
        return static::$redis;
    }
}
