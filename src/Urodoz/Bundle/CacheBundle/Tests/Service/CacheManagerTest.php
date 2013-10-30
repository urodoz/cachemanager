<?php

/*
 * This file is part of the UrodozCacheManager bundle.
 *
 * (c) Albert Lacarta <urodoz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Urodoz\Bundle\CacheBundle\Tests\Service;

use Urodoz\Bundle\CacheBundle\Lib\UrodozBaseTest;
use Urodoz\Bundle\CacheBundle\Service\CacheManager;
use Urodoz\Bundle\CacheBundle\Tests\Service\Mocks\PrefixGeneratorBadImplementation;
use Urodoz\Bundle\CacheBundle\Service\PrefixGeneratorInterface;
use Urodoz\Bundle\CacheBundle\Tests\Service\Mocks\PrefixGenerator;

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
        $container = $this->buildAndMarkSkippedCacheServersUndefined(true);
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
     * R
     * @endcode
     */
    public function testBasicSetAndRetrieveForRedis()
    {
        $container = $this->buildAndMarkSkippedCacheServersUndefined(false, true);
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

    /**
     * @code
     * phpunit -v --filter testCacheExceptionOnBadPrefixImplementation -c app/ vendor/urodoz/cachemanager/src/Urodoz/Bundle/CacheBundle/Tests/Service/CacheManagerTest.php
     * @endcode
     * @expectedException Urodoz\Bundle\CacheBundle\Exception\CacheException
     */
    public function testCacheExceptionOnBadPrefixImplementation()
    {
        $container = $this->buildAndMarkSkippedCacheServersUndefined();
        $cacheManager = $container->get("urodoz_cachemanager");
        $this->assertTrue($cacheManager instanceof CacheManager);

        //Mocking the service on the container
        $serviceBadImplementation = new PrefixGeneratorBadImplementation();
        $randomServiceId = uniqid();
        //Inject it on the container
        $container->set($randomServiceId, $serviceBadImplementation);

        $cacheManager->setPrefixGenerator($container, $randomServiceId);
    }

    public function getImplementationProvider()
    {
        return array(
            array("memcache"),
            array("redis"),
        );
    }

    /**
     * @code
     * phpunit -v --filter testPrefixGeneratorCustom -c app/ vendor/urodoz/cachemanager/src/Urodoz/Bundle/CacheBundle/Tests/Service/CacheManagerTest.php
     * @endcode
     * @dataProvider getImplementationProvider
     */
    public function testPrefixGeneratorCustom($implementationName)
    {
        $container = $this->buildAndMarkSkippedCacheServersUndefined(true, true);
        $cacheManager = $container->get("urodoz_cachemanager");
        $this->assertTrue($cacheManager instanceof CacheManager);

        //Mocking the service on the container
        $serviceImplementation = new PrefixGenerator();
        $this->assertTrue($serviceImplementation instanceof PrefixGeneratorInterface);
        $randomServiceId = uniqid();
        //Inject it on the container
        $container->set($randomServiceId, $serviceImplementation);

        $cacheManager->setPrefixGenerator($container, $randomServiceId);

        //Set and retrieve
        $cacheManager->implementation($implementationName)->set("foo", "bar");
        $this->assertEquals("bar", $cacheManager->implementation($implementationName)->get("foo"));

        //Change prefix and check bad retrieval of the key
        $container->get($randomServiceId)->setPrefixUsed(uniqid());
        $this->assertEquals(false, $cacheManager->implementation($implementationName)->get("foo"));

        //Set and retrieve again to check consistency
        $cacheManager->implementation($implementationName)->set("foo", "bar");
        $this->assertEquals("bar", $cacheManager->implementation($implementationName)->get("foo"));
    }

}
