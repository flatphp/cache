<?php namespace Flatphp\Cache;

use Flatphp\Cache\Adapter\AdapterInterface;
use Flatphp\Memstore\Conn;

/**
 * config e.g.
 * [
 *     'expiration' => 3600,
 *     'store' => 'redis'
 * ]
 * [
 *     'expiration' => 3600,
 *     'store' => [
 *         'driver' => 'redis',
 *         'host' => 'localhost',
 *         'port' => 6379
 *     ]
 * ]
 * @method static get($key, $default = null)
 * @method static set($key, $value, $expiration = null)
 * @method static delete($key)
 * @method static flush()
 * @method static increment($key, $offset = 1)
 * @method static decrement($key, $offset = 1)
 * @method static getData($key, $data_source = null, $expiration = null, $is_reset = false)
 * @method static setData($key, $data_source, $expiration = null)
 */
class Cache
{
    protected static $_config = [];
    protected static $_adapter;
    protected static $_backend;

    public static function init($cache)
    {
        if ($cache instanceof Backend) {
            self::$_backend = $cache;
        } elseif (is_array($cache)) {
            self::$_config = $cache;
        } else {
            throw new \InvalidArgumentException('Invalid Cache init parameter');
        }
    }

    public static function setAdapter(AdapterInterface $adapter)
    {
        self::$_adapter = $adapter;
    }

    /**
     * @return Backend
     */
    public static function getBackend()
    {
        if (null === self::$_backend) {
            self::$_backend = new Backend(self::_getAdapter(), self::$_config);
        }
        return self::$_backend;
    }

    /**
     * @return Adapter\AdapterInterface
     */
    protected static function _getAdapter()
    {
        if (!self::$_adapter) {
            if (empty(self::$_config['store'])) {
                throw new \InvalidArgumentException('Invalid cache store config');
            }
            self::$_adapter = self::_createAdapterMem(self::$_config['store']);
            unset(self::$_config['store']);
        }
        return self::$_adapter;
    }

    /**
     * @param $store
     * @return AdapterInterface
     */
    protected static function _createAdapterMem($store)
    {
        if (is_string($store)) {
            $name = self::_setName($store);
            $method = 'get'. $name;
            $mem = Conn::$method();
        } else {
            $name = self::_setName($store['driver']);
            unset($store['driver']);
            $conn = '\\Flatphp\\Memstore\\'. $name .'Conn';
            $conn = new $conn($store);
            $method = 'get'. $name;
            $mem = $conn->$method();
        }
        $class = __NAMESPACE__ .'\\Adapter\\'. $name;
        return new $class($mem);
    }

    protected static function _setName($name)
    {
        return str_replace('_', '', ucwords($name, '_'));
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