<?php namespace Flatphp\Cache\Adapter;

class Dummy implements AdapterInterface
{
    /**
     * {@inheritDoc}
     */
    public function get($key, $default = null)
    {
		return $default;
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value, $expiration = 0)
    {
		return true;
    }

    /**
     * {@inheritDoc}
     */
    public function delete($key)
    {
		return true;
    }

    /**
     * {@inheritDoc}
     */
    public function flush()
    {
    }

    /**
     * {@inheritDoc}
     */
    public function increment($key, $offset = 1)
    {
		return 0;
    }

    /**
     * {@inheritDoc}
     */
    public function decrement($key, $offset = 1)
    {
		return 0;
    }
}