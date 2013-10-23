<?php

namespace Urodoz\Bundle\CacheBundle\Service\Implementation;

interface CacheImplementationInterface
{

    /**
     * Inits the cache implementation
     *
     * @param array $connections
     */
    public function init(array $connections);

    /**
     * Sets a value on the cache implementation (non-indexed)
     *
     * @param string  $key
     * @param mixed   $value
     * @param integer $timeout
     */
    public function set($key, $value, $timeout=null);

    /**
     * Gets a value from cache implementation
     *
     * @param  string $key
     * @return mixed
     */
    public function get($key);

    /**
     * Sets an indexed value on the cache implementation
     *
     * @param string  $key
     * @param mixed   $value
     * @param integer $timeout
     */
    public function setIndexed($key, $value, $timeout=null);

    /**
     * Gets an indexed value from the cache implementation
     *
     * @param  string $key
     * @return mixed
     */
    public function getIndexed($key);

    /**
     * Return a boolean flag indicating the existance of the
     * key on the cache implementation
     *
     * @param  string  $key
     * @return boolean
     */
    public function has($key);

    /**
     * Return a boolean flag indicating the existance of the
     * key on the cache implementation (indexed)
     *
     * @param  string  $key
     * @return boolean
     */
    public function hasIndexed($key);

    /**
     * Removes a key from the cache implementation
     *
     * @param string $key
     */
    public function remove($key);

    /**
     * Removes an indexes key from the cache implementation
     *
     * @param string $pattern
     */
    public function removeIndexed($pattern);

}
