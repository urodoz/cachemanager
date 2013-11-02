<?php

/*
 * This file is part of the UrodozCacheManager bundle.
 *
 * (c) Albert Lacarta <urodoz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Urodoz\Bundle\CacheBundle\Tests\Event\Mocks;

use Urodoz\Bundle\CacheBundle\Event\CacheHitEvent;
use Urodoz\Bundle\CacheBundle\Event\MissedCacheHitEvent;

/**
 * Class event listener for testing purposes
 *
 * @author Albert Lacarta <urodoz@gmail.com>
 */
class CacheHitEventListener
{

    /**
     * @var array
     */
    private $events=array();

    /**
     * Adds the event to a collection of events
     *
     * @param CacheHitEvent $event
     */
    public function onCacheHitDispatched(CacheHitEvent $event)
    {
        $this->events[] = $event;
    }

    /**
     * Adds the event to a collection of events
     *
     * @param MissedCacheHitEvent $event
     */
    public function onCacheMissHitDispatched(MissedCacheHitEvent $event)
    {
        $this->events[] = $event;
    }

    /**
     * Clear the events array
     */
    public function clear()
    {
        $this->events = array();
    }

    /**
     * Return all events stored on the listener
     *
     * @return array
     */
    public function getEvents()
    {
        return $this->events;
    }

}
