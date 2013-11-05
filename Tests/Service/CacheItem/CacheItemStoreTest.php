<?php

/*
 * This file is part of the UrodozCacheManager bundle.
 *
 * (c) Albert Lacarta <urodoz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Urodoz\Bundle\CacheBundle\Tests\Service\CacheItem;

use Urodoz\Bundle\CacheBundle\Service\CacheItem\CacheItemStore;
use Urodoz\Bundle\CacheBundle\Lib\UrodozBaseTest;

/**
 * @code
 * phpunit -v -c app/ vendor/urodoz/cachemanager/Urodoz/Bundle/CacheBundle/Tests/Service/CacheItem/CacheItemStoreTest.php
 * @endcode
 */
class CacheItemStoreTest extends UrodozBaseTest
{

    public function providerInmutableDataThroughItem()
    {
        return array(
            array(uniqid()), //Random string
            array(1),
            array(rand(1000,900000)),
            array(0),
            array(false),
            array(true),
            array('{"foo":"bar"}'),
            array(101.98),
        );
    }

    /**
     * @code
     * phpunit -v --filter testInmutableDataThroughItem -c app/ vendor/urodoz/cachemanager/Urodoz/Bundle/CacheBundle/Tests/Service/CacheItem/CacheItemStoreTest.php
     * @endcode
     * @dataProvider providerInmutableDataThroughItem
     */
    public function testInmutableDataThroughItem($item)
    {
        $cacheItemStore = new CacheItemStore();
        $cacheItemStore->createFromData($item, 10);

        $dataToBeCached = $cacheItemStore->getCacheData();

        $cacheItemStore2 = new CacheItemStore();
        $cacheItemStore2->hydrateFromCacheData($dataToBeCached);

        $this->assertEquals($cacheItemStore2->getData(), $item);
    }

    /**
     * @code
     * phpunit -v --filter testBadHashNullDataReturned -c app/ vendor/urodoz/cachemanager/Urodoz/Bundle/CacheBundle/Tests/Service/CacheItem/CacheItemStoreTest.php
     * @endcode
     * @dataProvider providerInmutableDataThroughItem
     */
    public function testBadHashNullDataReturned($item)
    {
        $cacheItemStore = new CacheItemStore();
        $cacheItemStore->createFromData($item, 10);

        $dataToBeCached = $cacheItemStore->getCacheData();
        $dataAsObject = json_decode($dataToBeCached);
        $dataAsObject->hash = sha1(uniqid());
        $dataToBeCached = json_encode($dataAsObject);

        $cacheItemStore2 = new CacheItemStore();
        $cacheItemStore2->hydrateFromCacheData($dataToBeCached);

        $this->assertEquals($cacheItemStore2->getData(), null);
    }

    /**
     * @code
     * phpunit -v --filter testExpirationCausesNull -c app/ vendor/urodoz/cachemanager/Urodoz/Bundle/CacheBundle/Tests/Service/CacheItem/CacheItemStoreTest.php
     * @endcode
     * @dataProvider providerInmutableDataThroughItem
     */
    public function testExpirationCausesNull($item)
    {
        $cacheItemStore = new CacheItemStore();
        $cacheItemStore->createFromData($item, 0);

        $dataToBeCached = $cacheItemStore->getCacheData();

        $cacheItemStore2 = new CacheItemStore();
        $cacheItemStore2->hydrateFromCacheData($dataToBeCached);

        $this->assertEquals($cacheItemStore2->getData(), null);
    }

}
