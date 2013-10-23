<?php

namespace Urodoz\Bundle\CacheBundle\Service;

use Urodoz\Bundle\CacheBundle\Service\Implementation\CacheImplementationInterface;
use Urodoz\Bundle\CacheBundle\Service\Implementation\MemcacheImplementation;
use Urodoz\Bundle\CacheBundle\Service\Implementation\RedisImplementation;

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

            $implementation = $this->factoryImplementation($key);
            $implementation->init($this->connections[$key]);
            $this->implementations[$key] = $implementation;
        }

        //Return implementation
        return $this->implementations[$key];
    }

    /**
     * @return CacheImplementationInterface
     */
    private function factoryImplementation($key)
    {
        switch ($key) {
            case "redis":
                return new RedisImplementation();
                break;
            case "memcache":
                return new MemcacheImplementation();
                break;
            default:
                throw new \Exception("Implementation for {".$key."} not found");
                break;
        }
    }

}
