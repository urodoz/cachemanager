<?php

namespace Urodoz\Bundle\CacheBundle\Tests\Service;

use Urodoz\Bundle\CacheBundle\Lib\UrodozBaseTest;
use Urodoz\Bundle\CacheBundle\Service\CacheManager;

/**
 * @code
 * phpunit -v -c app/ vendor/urodoz/cachemanager/src/Urodoz/Bundle/CacheBundle/Tests/Service/CacheManagerTest.php
 * @endcode
 */
class CacheManagerTest extends UrodozBaseTest
{

    /**
     * @code
     * phpunit -v --group UrodozCacheManager_basicRetrieveAndDelete -c app/ vendor/urodoz/cachemanager/src/Urodoz/Bundle/CacheBundle/Tests/Service/CacheManagerTest.php
     * @endcode
     * @group UrodozCacheManager_basicRetrieveAndDelete
     */
    public function testBasicSetAndRetrieveForMemcache()
    {
        $container = $this->buildAndMarkSkippedCacheServersUndefined();
        $cacheManager = $container->get("urodoz_cachemanager");
        $this->assertTrue($cacheManager instanceof CacheManager);

        $randomKey = uniqid();
        $cacheManager->implementation("memcache")->set($randomKey, "foo:bar", 10);

        $this->assertEquals("foo:bar", $cacheManager->implementation("memcache")->get($randomKey));

        return array(
            "memcache" => $cacheManager->implementation("memcache"),
            "randomKey" => $randomKey,
        );
    }

    /**
     * @code
     * phpunit -v --group UrodozCacheManager_basicRetrieveAndDelete -c app/ vendor/urodoz/cachemanager/src/Urodoz/Bundle/CacheBundle/Tests/Service/CacheManagerTest.php
     * @endcode
     * @depends testBasicSetAndRetrieveForMemcache
     * @group UrodozCacheManager_basicRetrieveAndDelete
     */
    public function testRetrieveAndDelete($data)
    {
        $memcacheImplementation = $data["memcache"];
        $randomKey = $data["randomKey"];

        $this->assertEquals("foo:bar", $memcacheImplementation->get($randomKey));
        $memcacheImplementation->remove($randomKey);
        $this->assertFalse("foo:bar"==$memcacheImplementation->get($randomKey));
    }

    /**
     * @code
     * phpunit -v --filter testBasicSetAndRetrieveForRedis -c app/ vendor/urodoz/cachemanager/src/Urodoz/Bundle/CacheBundle/Tests/Service/CacheManagerTest.php
     * @endcode
     */
    public function testBasicSetAndRetrieveForRedis()
    {
        $container = $this->buildAndMarkSkippedCacheServersUndefined();
        $cacheManager = $container->get("urodoz_cachemanager");
        $this->assertTrue($cacheManager instanceof CacheManager);

        $randomKey = uniqid();
        $cacheManager->implementation("redis")->set($randomKey, "foo:bar", 10);

        $this->assertEquals("foo:bar", $cacheManager->implementation("redis")->get($randomKey));

        return array(
            "redis" => $cacheManager->implementation("redis"),
            "randomKey" => $randomKey,
        );
    }

}
