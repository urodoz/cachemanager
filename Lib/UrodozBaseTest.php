<?php

/*
 * This file is part of the UrodozCacheManager bundle.
 *
 * (c) Albert Lacarta <urodoz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Urodoz\Bundle\CacheBundle\Lib;

use Urodoz\Bundle\CacheBundle\DependencyInjection\CacheManagerParameterKeys;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UrodozBaseTest extends WebTestCase
{

    /**
     * Marks test skipped if no cache server has been added
     *
     * @return ContainerInterface
     */
    protected function buildAndMarkSkippedCacheServersUndefined(
            $forceConfMemcache=false,
            $forceCongRedis=false
            )
    {
        $client = static::createClient();
        $container = $client->getContainer();

        $memcacheKeys = $container->getParameter(CacheManagerParameterKeys::PARAM_KEY_MEMCACHE_CONNECTIONS);
        $redisKeys = $container->getParameter(CacheManagerParameterKeys::PARAM_KEY_REDIS_CONNECTIONS);

        //Needs at least one configuration be a workable service
        if(empty($memcacheKeys) && empty($redisKeys)) $this->markTestSkipped("The array of cache connections is empty, no memcache or redis servers connections defined");
        if($forceConfMemcache && empty($memcacheKeys)) $this->markTestSkipped("The array of memcache connections is empty, the test requires a memcache configuration to continue");
        if($forceConfMemcache && empty($redisKeys)) $this->markTestSkipped("The array of redis connections is empty, the test requires a redis configuration to continue");

        return $container;
    }

    public function getImplementationProvider()
    {
        return array(
            array("memcache"),
            array("redis"),
        );
    }

}
