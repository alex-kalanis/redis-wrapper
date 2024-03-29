Redis Wrapper
=============

![Build Status](https://github.com/alex-kalanis/redis-wrapper/actions/workflows/code_checks.yml/badge.svg)
[![Latest Stable Version](https://poser.pugx.org/alex-kalanis/redis-wrapper/v/stable.svg?v=1)](https://packagist.org/packages/alex-kalanis/redis-wrapper)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.4-8892BF.svg)](https://php.net/)
[![Downloads](https://img.shields.io/packagist/dt/alex-kalanis/redis-wrapper.svg?v1)](https://packagist.org/packages/alex-kalanis/redis-wrapper)
[![License](https://poser.pugx.org/alex-kalanis/redis-wrapper/license.svg?v=1)](https://packagist.org/packages/alex-kalanis/redis-wrapper)

Use Redis records as they were files. You can use either Redis module for PHP or Predis library for accesing the cluster.

# Installation

```bash
composer.phar require alex-kalanis/redis-wrapper
```

(Refer to [Composer Documentation](https://github.com/composer/composer/blob/master/doc/00-intro.md#introduction) if you are not
familiar with composer)

# Usages

Just only initialize wrapper with your Redis instance.

Redis module:

```php
    $redis = new \Redis();
    RedisWrapper::setRedis($redis);
    RedisWrapper::register();
```

Predis library:

```php
    $redis = new \Predis\Client();
    PredisWrapper::setRedisClient($redis);
    PredisWrapper::register();
```

Then for both:

```php
    file_get_contents('redis://any/key/in/redis/storage');
    file_put_contents('redis://another/key/in/storage', 'add something');
```

For using storage just look onto kw_storage readme.
