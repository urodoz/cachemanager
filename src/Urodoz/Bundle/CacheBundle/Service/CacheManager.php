<?php

namespace Urodoz\Bundle\CacheBundle\Service;

use Urodoz\Bundle\CacheBundle\Service\Implementation\CacheImplementationInterface;
use Urodoz\Bundle\CacheBundle\Service\Implementation\MemcacheImplementation;
use Urodoz\Bundle\CacheBundle\Service\Implementation\RedisImplementation;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Urodoz\Bundle\CacheBundle\Service\PrefixGeneratorInterface;
use Urodoz\Bundle\CacheBundle\Exception\CacheException;

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

    /**
     * Prefix generator service
     *
     * @var PrefixGeneratorInterface
     */
    private $prefixGenerator;

    public function setGenericConnections($key, array $connections)
    {
        if(!isset($this->connections[$key])) $this->connections[$key] = array();
        foreach ($connections as $configConnection) {
            $this->connections[$key][] = $configConnection;
        }
    }

    /**
     * Sets the prefix generator service if this service is defined
     * and exists on the container
     *
     * @param  ContainerInterface $container
     * @param  string             $service
     * @throws CacheException
     */
    public function setPrefixGenerator(ContainerInterface $container, $service = null)
    {
        if (!is_null($service) && $container->has($service)) {
            $serviceFromContainer = $container->get($service);
            if (!$serviceFromContainer instanceof PrefixGeneratorInterface) {
                throw new CacheException(
                        "A PrefixGenerator service (id:".$service.")"
                        ." has been registered on CacheManager, but does not"
                        ." implement the PrefixGeneratorInterface as expected"
                        );
            }
            //Adding service to instance
            $this->prefixGenerator = $serviceFromContainer;
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
                return new RedisImplementation($this->prefixGenerator);
                break;
            case "memcache":
                return new MemcacheImplementation($this->prefixGenerator);
                break;
            default:
                throw new \Exception("Implementation for {".$key."} not found");
                break;
        }
    }

}
