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
 * Event throwed when a cache hit is done succesfully
 *
 * @author Albert Lacarta <urodoz@gmail.com>
 */
class CacheHitEvent extends Event
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
     * @var string
     */
    private $content;

    /**
     * Class constructor
     *
     * @param string $implementation
     * @param string $cacheKey
     * @param string $content
     */
    public function __construct($implementation, $cacheKey, $content)
    {
        $this->implementation = $implementation;
        $this->cacheKey = $cacheKey;
        $this->content = $content;
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

    /**
     * Return content from cache
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

}
