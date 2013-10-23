<?php

namespace Urodoz\Bundle\CacheBundle\Service;

use Urodoz\Bundle\CacheBundle\Service\Implementation\CacheImplementationInterface;
use Urodoz\Bundle\CacheBundle\Service\Implementation\MemcacheImplementation;

class CacheManager
{

    /**
     * Array of connections
     *
     * @var array
     */
    private $connections=array();

    /**
     * Array of implementations
     *
     * @var array
     */
    private $implementations=array();

    public function setGenericConnections($key, array $connections)
    {
        if(!isset($this->connections[$key])) $this->connections[$key] = array();
        foreach ($connections as $configConnection) {
            $this->connections[$key][] = $configConnection;
        }
    }

    /**
     * @return CacheImplementationInterface
     */
    public function implementation($key)
    {
        //Check connections
        if (!isset($this->connections[$key]) || empty($this->connections[$key])) {
            throw new \Exception("Cannot connect to {".$key."} implementation. None defined on configuration");
        }
        //Create implementation with connections
        if (!isset($this->implementations[$key])) {
            $implementation = new MemcacheImplementation();
            $implementation->init($this->connections[$key]);
            $this->implementations[$key] = $implementation;
        }

        //Return implementation
        return $this->implementations[$key];
    }

}
