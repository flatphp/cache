<?php namespace Flatphp\Cache;

use Flatphp\Memstore\Store;

/**
 * config e.g.
 * [
 *     'expiration' => 3600,
 *     'storage' => 'redis'
 * ]
 */
class Cache
{
    protected static $_config = [];

    public static function config(array $config)
    {
        if (empty($config['storage'])) {
            throw new \InvalidArgumentException('cache missing storage config');
        }
        self::$_config = $config;
    }


    /**
     * @param null $name
     * @return Adapter\AdapterInterface
     */
    public static function getAdapter($name = null)
    {
        static $adapter = null;
        if (null === $adapter) {
            if (!$name) {
                $name = ucfirst(strtolower(self::$_config['storage']));
            }
            $class = __NAMESPACE__ .'\\Adapter\\'. $name;
            $method = 'get'. $name;
            $adapter = new $class(Store::$method());
        }
        return $adapter;
    }

    /**
     * @return Backend
     */
    public static function getBackend()
    {
        static $backend = null;
        if (null === $backend) {
            $backend = new Backend(self::getAdapter(), self::$_config);
        }
        return $backend;
    }

    /**
     * @param string $method
     * @param array $args
     * @return mixed
     */
    public static function __callStatic($method, $args = [])
    {
        return call_user_func_array([self::getBackend(), $method], $args);
    }
}