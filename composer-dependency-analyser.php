<?php

/**
 * Dependency analyzer configuration
 * @link https://github.com/shipmonk-rnd/composer-dependency-analyser
 */

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;
use ShipMonk\ComposerDependencyAnalyser\Config\ErrorType;

$config = new Configuration();

return $config
    // ignore errors on specific packages and paths
    ->ignoreErrorsOnPackageAndPath('alex-kalanis/kw_storage', __DIR__ . '/src/RedisStorage.php', [ErrorType::DEV_DEPENDENCY_IN_PROD])
    ->ignoreErrorsOnPackageAndPath('alex-kalanis/kw_storage', __DIR__ . '/src/PredisStorage.php', [ErrorType::DEV_DEPENDENCY_IN_PROD])
    ->ignoreErrorsOnPackageAndPath('predis/predis', __DIR__ . '/src/PredisStorage.php', [ErrorType::DEV_DEPENDENCY_IN_PROD])
    ->ignoreErrorsOnPackageAndPath('predis/predis', __DIR__ . '/src/PredisWrapper.php', [ErrorType::DEV_DEPENDENCY_IN_PROD])
    ->ignoreErrorsOnPackageAndPath('predis/predis', __DIR__ . '/src/PredisWrapper/TPredis.php', [ErrorType::DEV_DEPENDENCY_IN_PROD])
;