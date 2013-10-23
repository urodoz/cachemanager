<?php

namespace Urodoz\Bundle\CacheBundle\Service\Implementation;

use Predis;
use Urodoz\Bundle\CacheBundle\Service\Implementation\CacheImplementationInterface;

class RedisImplementation implements CacheImplementationInterface
{

    /**
     * @var Predis\Client
     */
    private $client;

    /**
     * {@inheritDoc}
     */
    public function init(array $connections)
    {
        if(!class_exists("Predis")) throw new \Exception("'Predis' class does not exist. Please install it to be able to use the Memcache connections");

        $preparedConnections = array();
        foreach ($connections as $connection) {
            $explodedConnection = explode(":", $connection);
            $preparedConnections[] = array("host" => $connection[0], "port" => (int) $connection[1]);
        }
        //Connect to multiple instances
        $this->client = new Predis\Client($preparedConnections);
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value, $timeout=null)
    {
        return $this->client->set($key, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function get($key)
    {
        return $this->client->get($key);
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

    }

    /**
     * {@inheritDoc}
     */
    public function removeIndexed($pattern)
    {

    }

}
