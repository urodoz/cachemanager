<?php

/*
 * This file is part of the UrodozCacheManager bundle.
 *
 * (c) Albert Lacarta <urodoz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
        if(!class_exists("\\Predis\\Client")) throw new \Exception("'Predis' class does not exist. Please install it to be able to use the Memcache connections");

        $preparedConnections = array();
        foreach ($connections as $connectionDescription) {
            $preparedConnections[] = array(
                "scheme" => "tcp",
                "host" => $connectionDescription["host"],
                "port" => $connectionDescription["port"],
            );
        }
        $this->client = new Predis\Client($preparedConnections);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return "Redis";
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
