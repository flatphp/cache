<?php namespace Flatphp\Cache;

use Flatphp\Cache\Adapter\AdapterInterface;

class Backend
{
    /**
     * @var AdapterInterface
     */
    protected $_adapter;
    protected $_config;
    
    public function __construct(AdapterInterface $adapter, array $config = [])
    {
        $this->_adapter = $adapter;
        $this->_config = $config;
    }
    
    /**
     * Get cache data
     * @param $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->_adapter->get($key, $default);
    }

    /**
     * Set cache data
     * @param string $key
     * @param mixed $value
     * @param int $expiration second,0 for forever
     */
    public function set($key, $value, $expiration = null)
    {
        if (null === $expiration) {
            $expiration = isset($this->_config['expiration']) ? (int)$this->_config['expiration'] : 0;
        }
        return $this->_adapter->set($key, $value, $expiration);
    }

    /**
     * Delete cache data
     * @param string $key
     */
    public function delete($key)
    {
        return $this->_adapter->delete($key);
    }

    /**
     * Remove all items from the cache.
     * @return void
     */
    public function flush()
    {
        $this->_adapter->flush();
    }

    /**
     * Increment the value of an item in the cache.
     * @param  string  $key
     * @param  int   $offset
     * @return int|false return the new value or false
     */
    public function increment($key, $offset = 1)
    {
        return $this->_adapter->increment($key, $offset);
    }

    /**
     * Decrement the value of an item in the cache.
     * @param  string  $key
     * @param  int   $offset
     * @return int|false return the new value or false
     */
    public function decrement($key, $offset = 1)
    {
        return $this->_adapter->decrement($key, $offset);
    }

    /**
     * Get the data
     * If date not exists, the data from the callback will be set to the cache
     *
     * @param string $key
     * @param mixed $data_source
     * @param int $expiration
     * @param bool $is_reset
     * @return mixed
     */
    public function getData($key, $data_source = null, $expiration = null, $is_reset = false)
    {
        $key = strtolower($key);
        $data = $this->get($key);
        if (null === $data || $is_reset) {
            $data = $this->setData($key, $data_source, $expiration);
        }
        return $data;
    }

    /**
     * Set the data to the cache
     *
     * @param string $key
     * @param mixed $data_source
     * @param int $expiration
     * @return mixed
     */
    public function setData($key, $data_source, $expiration = null)
    {
        is_callable($data_source) && $data_source = $data_source();
        if (!empty($data_source)) {
            $key = strtolower($key);
            $this->set($key, $data_source, $expiration);
        }
        return $data_source;
    }
}