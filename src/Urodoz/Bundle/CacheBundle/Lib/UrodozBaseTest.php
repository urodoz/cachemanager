<?php

namespace Urodoz\Bundle\CacheBundle\Lib;

use Urodoz\Bundle\CacheBundle\DependencyInjection\UrodozCacheExtension;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UrodozBaseTest extends WebTestCase
{

    /**
     * Marks test skipped if no cache server has been added
     *
     * @return ContainerInterface
     */
    protected function buildAndMarkSkippedCacheServersUndefined()
    {
        $client = static::createClient();
        $container = $client->getContainer();

        $memcacheKeys = $container->getParameter(UrodozCacheExtension::PARAM_KEY_MEMCACHE_IMPLEMENTATIONS);
        $redisKeys = $container->getParameter(UrodozCacheExtension::PARAM_KEY_REDIS_IMPLEMENTATIONS);

        //Needs at least one configuration be a workable service
        if(empty($memcacheKeys) && empty($redisKeys)) $this->markTestSkipped("The array of cache connections is empty, no memcache or redis servers connections defined");

        return $container;
    }

}
