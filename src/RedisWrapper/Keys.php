<?php

namespace kalanis\RedisWrapper\RedisWrapper;


use kalanis\RedisWrapper\Shared;


/**
 * Class Keys
 * @package kalanis\RedisWrapper\RedisWrapper
 * Wrapper to plug Redis info into PHP - directory part
 */
class Keys extends Shared\Keys
{
    use TRedis;
}
