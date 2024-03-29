<?php

namespace kalanis\RedisWrapper\Shared;


/**
 * Class AOperations
 * @package kalanis\RedisWrapper\Shared
 * Wrapper to plug Redis info into PHP - directory part
 */
abstract class AOperations
{
    /**
     * @param string $path
     * @return string
     */
    protected function parsePath(string $path): string
    {
        $into = parse_url($path, PHP_URL_PATH);
        return strval($into);
    }
}
