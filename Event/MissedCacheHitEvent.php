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

use Symfony\Component\EventDispatcher\Event;

/**
 * Event throwed when a cache hit is missed
 *
 * @author Albert Lacarta <urodoz@gmail.com>
 */
class MissedCacheHitEvent extends Event
{

    /**
     * @var string
     */
    private $implementation;

    /**
     * @var string
     */
    private $cacheKey;

    /**
     * Class constructor
     *
     * @param string $implementation
     * @param string $cacheKey
     */
    public function __construct($implementation, $cacheKey)
    {
        $this->implementation = $implementation;
        $this->cacheKey = $cacheKey;
    }

    /**
     * Return implementation
     *
     * @return string
     */
    public function getImplementation()
    {
        return $this->implementation;
    }

    /**
     * Return cache key
     *
     * @return string
     */
    public function getCacheKey()
    {
        return $this->cacheKey;
    }

}
