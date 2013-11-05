<?php

/*
 * This file is part of the UrodozCacheManager bundle.
 *
 * (c) Albert Lacarta <urodoz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Urodoz\Bundle\CacheBundle\Service\CacheItem;

/**
 * Interface all CacheItemStore implements
 *
 * @author Albert Lacarta <urodoz@gmail.com>
 */
interface CacheItemStoreInterface
{

    /**
     * Hydrates the object from mixed data
     *
     * @param string  $data
     * @param integer $expirationInSeconds
     */
    public function createFromData($data, $expirationInSeconds);

    /**
     * Hydrates from data fetched from Cache
     * implementation
     *
     * @param string $cacheData
     */
    public function hydrateFromCacheData($cacheData);

    /**
     * Retrieves the data stored or hydrated ready
     * to be used for the data requester
     *
     * @return mixed
     */
    public function getData();

    /**
     * Gets the data as its stored on the cache implementation,
     * with the structure ready to create a CacheItemStore to
     * fetch the data again
     *
     * @return string
     */
    public function getCacheData();

}
