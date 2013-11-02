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
 * Event throwed when a cache key is being used
 * to update the cache key before usage
 *
 * @author Albert Lacarta <urodoz@gmail.com>
 */
class UpdateCacheKeyEvent extends Event
{

    /**
     * @var string
     */
    private $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * Returns the key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Updates the key adding a prefix
     *
     * @param string $prefix
     */
    public function addPrefix($prefix)
    {
        $this->key = $prefix . $this->key;
    }

    /**
     * Updates the key adding a suffix
     *
     * @param string $suffix
     */
    public function addSuffix($suffix)
    {
        $this->key = $this->key . $suffix;
    }

}
