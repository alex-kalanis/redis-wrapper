<?php

namespace kalanis\RedisWrapper\PredisWrapper;


use kalanis\RedisWrapper\Shared;


/**
 * Wrapper to plug Redis info into PHP - directory part
 */
class Keys extends Shared\Keys
{
    use TPredis;
}
