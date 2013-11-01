<?php

/*
 * This file is part of the UrodozCacheManager bundle.
 *
 * (c) Albert Lacarta <urodoz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Urodoz\Bundle\CacheBundle\Service\Collector;

use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Urodoz\Bundle\CacheBundle\Event\CacheHitEvent;
use Urodoz\Bundle\CacheBundle\Event\MissedCacheHitEvent;

class UsageCollector extends DataCollector
{

    /**
     * @var array
     */
    private $hits=array();

    /**
     * @var array
     */
    private $missedHits=array();

    /**
     * Return the hits array
     *
     * @return array
     */
    public function hits()
    {
        return $this->hits;
    }

    /**
     * Return the missed hits array
     *
     * @return array
     */
    public function missedHits()
    {
        return $this->missedHits;
    }

    /**
     * {@inheritDoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        //No collect form request
    }

    /**
     * Listener to missed cache hit event dispatched by cache manager
     *
     * @param MissedCacheHitEvent $event
     */
    public function onMissedHit(MissedCacheHitEvent $event)
    {
        $this->missedHits[] = $event;
    }

    /**
     * Listener to cache hit event dispatched by cache manager
     *
     * @param CacheHitEvent $event
     */
    public function onSuccessfullHit(CacheHitEvent $event)
    {
        $this->hits[] = $event;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'urodozMemcache';
    }

}
