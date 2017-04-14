# Cache
Light cache component;

relate: https://github.com/flatphp/memstore


## Install
```php
composer require "flatphp/cache"
```



## Init Config

```PHP
use Flatphp\Memstore\Conn;
use Flatphp\Cache\Cache;

Conn::init(array(
    'memcache' => ['host' => '127.0.0.1', 'port' => 11211]
));

Cache::init(array(
    'expiration' => 3600, // the default expiration, 0 forever[default]
    'storage' => 'memcache',
));

// -------- OR --------------------

Cache::init(array(
    'expiration' => 3600, // the default expiration, 0 forever[default]
    'storage' => array(
        'driver' => 'redis',
	'host' => 'localhost',
	'port' => 6379
    )
));

```



## Useage
```PHP
use Flatphp\Cache\Cache;

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