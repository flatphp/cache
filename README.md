# Sutile\Cache
This is a light cache component;
Based on memstore (memcache, memcached, redis)


## Install
```php
composer require "flatphp/cache"
```



## Init Config

```PHP
\Flatphp\Cache\Cache::config(array(
    'expiration' => 3600, // the default expiration, 0 forever[default]
    'storage' => 'memcache',
));
```



## Useage
```PHP
Cache::set('test', 1, 60);
echo Cache::get('test');
Cache::delete('test');
Cache::flush();

Cache::increment('test', 2);
Cache::decrement('test', 2);

Cache::getData('test', function(){
    return 'hello';
});

```