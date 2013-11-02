<?php

/*
 * This file is part of the UrodozCacheManager bundle.
 *
 * (c) Albert Lacarta <urodoz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Urodoz\Bundle\CacheBundle\Event;

/**
 * Store the event names to be dispatched
 *
 * @author Albert Lacarta <urodoz@gmail.com>
 */
final class EventStore
{

    /**
     * Identifier of the event dispatched when a successfull
     * cache hit has been performed
     *
     * @var string
     */
    const EVENT_CACHE_HIT = "urodoz.events.cachehit";

    /**
     * Identifier of the event dispatched when a
     * cache hit misses
     *
     * @var string
     */
    const EVENT_MISSED_CACHE_HIT = "urodoz.events.missed_cachehit";

    /**
     * Identifier of the event dispatched when the cache
     * key can be updated with any listener
     *
     * @var string
     */
    const UPDATE_CACHE_KEY = "urodoz.events.update_cachekey";

}
