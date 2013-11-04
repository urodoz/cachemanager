<?php

/*
 * This file is part of the UrodozCacheManager bundle.
 *
 * (c) Albert Lacarta <urodoz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Urodoz\Bundle\CacheBundle\Tests\Event;

use Urodoz\Bundle\CacheBundle\Lib\UrodozBaseTest;
use Urodoz\Bundle\CacheBundle\Service\CacheManager;
use Urodoz\Bundle\CacheBundle\Tests\Event\Mocks\CacheHitEventListener;
use Urodoz\Bundle\CacheBundle\Event\EventStore;
use Urodoz\Bundle\CacheBundle\Event\CacheHitEvent;
use Urodoz\Bundle\CacheBundle\Event\MissedCacheHitEvent;

/**
 * @code
 * phpunit -v -c app/ vendor/urodoz/cachemanager/Urodoz/Bundle/CacheBundle/Tests/Event/CacheHitEventTest.php
 * @endcode
 */
class CacheHitEventTest extends UrodozBaseTest
{

    /**
     * @code
     * phpunit -v --filter testEventDispatched -c app/ vendor/urodoz/cachemanager/Urodoz/Bundle/CacheBundle/Tests/Event/CacheHitEventTest.php
     * @endcode
     * @dataProvider getImplementationProvider
     */
    public function testEventDispatched($implementationName)
    {
        $container = $this->buildAndMarkSkippedCacheServersUndefined(true, true);
        $eventDispatcher = $container->get("event_dispatcher");
        $cacheManager = $container->get("urodoz_cachemanager");
        $this->assertTrue($cacheManager instanceof CacheManager);

        $listener = new CacheHitEventListener();
        $eventDispatcher->addListener(EventStore::EVENT_CACHE_HIT, array($listener, "onCacheHitDispatched"));
        $eventDispatcher->addListener(EventStore::EVENT_MISSED_CACHE_HIT, array($listener, "onCacheMissHitDispatched"));

        //Get a successfull cache hit
        $cacheManager->implementation($implementationName);
        $cacheManager->set("foo", "bar");
        $this->assertCount(0, $listener->getEvents());
        $cacheManager->get("foo");
        $this->assertCount(1, $listener->getEvents());

        $events = $listener->getEvents();
        $event = $events[0];

        //Check data of the event dispatched
        $this->assertTrue($event instanceof CacheHitEvent);
        $this->assertEquals("bar", $event->getContent());
        $this->assertRegExp('/foo/', $event->getCacheKey());
        $this->assertEquals(strtolower($implementationName), strtolower($event->getImplementation()));

        /*
         * Missing cache hit
         */
        $listener->clear();
        $randomKey = uniqid("TEST_MISSED_HIT_");
        $cacheManager->get($randomKey);
        $this->assertCount(1, $listener->getEvents());

        $events = $listener->getEvents();
        $event = $events[0];

        //Check data of the event dispatched
        $this->assertTrue($event instanceof MissedCacheHitEvent);
        $this->assertRegExp('/'.$randomKey.'/', $event->getCacheKey());
        $this->assertEquals(strtolower($implementationName), strtolower($event->getImplementation()));
    }

}
