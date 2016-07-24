<?php namespace Flatphp\Cache\Adapter;
use Flatphp\Memstore\Redis as RedisStore;

class Redis implements AdapterInterface
{
    /**
     * @var RedisStore
     */
    protected $_redis = null;

    public function __construct(RedisStore $redis)
    {
        $this->_redis = $redis;
    }

    /**
     * {@inheritDoc}
     */
    public function get($key, $default = null)
    {
        $value = $this->_redis->get($key);
        if ($value === false) {
            return $default;
        } elseif (is_numeric($value)) {
            return $value;
        } else {
            return unserialize($value);
        }
    }


    /**
     * {@inheritDoc}
     */
    public function set($key, $value, $expiration = 0)
    {
        $value = is_numeric($value) ? $value : serialize($value);
        if ($expiration) {
            return $this->_redis->setex($key, $expiration, $value);
        } else {
            return $this->_redis->set($key, $value);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function delete($key)
    {
        return $this->_redis->delete($key);
    }

    /**
     * {@inheritDoc}
     */
    public function flush()
    {
        $this->_redis->flushDB();
    }

    /**
     * {@inheritDoc}
     */
    public function increment($key, $offset = 1)
    {
        return $this->_redis->incrBy($key, $offset);
    }

    /**
     * {@inheritDoc}
     */
    public function decrement($key, $offset = 1)
    {
        return $this->_redis->decrBy($key, $offset);
    }
}