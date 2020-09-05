<?php

namespace RedisWrapper\RedisWrapper;

use RedisWrapper\Shared;

/**
 * Wrapper to plug Redis info into PHP - directory part
 */
class Keys extends Shared\Keys
{
    use TRedis;
}
