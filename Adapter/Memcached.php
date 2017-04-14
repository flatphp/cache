<?php namespace Flatphp\Cache\Adapter;

class Memcached implements AdapterInterface
{
    /**
     * @var \Memcached
     */
    protected $_mc = null;

    public function __construct(\Memcached $mc)
    {
        $this->_mc = $mc;
    }

    /**
     * {@inheritDoc}
     */
    public function get($key, $default = null)
    {
        $value = $this->_mc->get($key);
        return ($value === false) ? $default : $value;
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value, $expiration = 0)
    {
        return $this->_mc->set($key, $value, $expiration);
    }

    /**
     * {@inheritDoc}
     */
    public function delete($key)
    {
        return $this->_mc->delete($key);
    }

    /**
     * {@inheritDoc}
     */
    public function flush()
    {
        $this->_mc->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function increment($key, $offset = 1)
    {
        return $this->_mc->increment($key, $offset);
    }

    /**
     * {@inheritDoc}
     */
    public function decrement($key, $offset = 1)
    {
        return $this->_mc->decrement($key, $offset);
    }
}