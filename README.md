Redis Wrapper
=============

Use Redis records as they were files. You can use either Redis module for PHP or Predis library for accesing the cluster.

# Installation

```json
{
    "require": {
        "alex-kalanis/redis-wrapper": "1.0"
    }
}
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

For using storage just look onto kv_storage readme.
