<?php

namespace Urodoz\Bundle\CacheBundle\Service\Implementation;

use Urodoz\Bundle\CacheBundle\Service\Implementation\CacheImplementationInterface;

class MemcacheImplementation implements CacheImplementationInterface
{

    const DEFAULT_TIMEOUT = 36000; //10 Hours by default

    /**
     * @var \Memcache
     */
    private $memcache;

    /**
     * {@inheritDoc}
     */
    public function init(array $connections)
    {
        if(!class_exists("\\Memcache")) throw new \Exception("'Memcache' class does not exist. Please install it to be able to use the Memcache connections");

        $this->memcache = new \Memcache();
        foreach ($connections as $connection) {
            $explodedConnection = explode(":", $connection);
            $this->memcache->addserver($explodedConnection[0], $explodedConnection[1]);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value, $timeout=null)
    {
        if(is_null($timeout)) $timeout = static::DEFAULT_TIMEOUT;

        return $this->memcache->set($key, $value, MEMCACHE_COMPRESSED, $timeout);
    }

    /**
     * {@inheritDoc}
     */
    public function get($key)
    {
        return $this->memcache->get($key, MEMCACHE_COMPRESSED);
    }

    /**
     * {@inheritDoc}
     */
    public function setIndexed($key, $value, $timeout=null)
    {

    }

    /**
     * {@inheritDoc}
     */
    public function getIndexed($key)
    {

    }

    /**
     * {@inheritDoc}
     */
    public function has($key)
    {
        return  ($this->get($key));
    }

    /**
     * {@inheritDoc}
     */
    public function hasIndexed($key)
    {

    }

    /**
     * {@inheritDoc}
     */
    public function remove($key)
    {
        $this->memcache->delete($key);
    }

    /**
     * {@inheritDoc}
     */
    public function removeIndexed($pattern)
    {

    }

}
