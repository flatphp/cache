<?php namespace Flatphp\Cache\Adapter;

class Redis implements AdapterInterface
{
    /**
     * @var \Redis
     */
    protected $_redis = null;

    public function __construct(\Redis $redis)
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
        } else {
            return $this->unserialize($value);
        }
    }


    /**
     * {@inheritDoc}
     */
    public function set($key, $value, $expiration = 0)
    {
        $value = $this->serialize($value);
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
        $this->_redis->delete($key);
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

    /**
     * Serialize the value.
     * @param  mixed  $value
     * @return mixed
     */
    protected function serialize($value)
    {
        return is_numeric($value) ? $value : serialize($value);
    }

    /**
     * Unserialize the value.
     * @param  mixed  $value
     * @return mixed
     */
    protected function unserialize($value)
    {
        return is_numeric($value) ? $value : unserialize($value);
    }
}