<?php

/*
 * This file is part of the UrodozCacheManager bundle.
 *
 * (c) Albert Lacarta <urodoz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Urodoz\Bundle\CacheBundle\DependencyInjection;

/**
 * Holds the parameter keys of the container interface
 *
 * @author Albert Lacarta <urodoz@gmail.com>
 */
final class CacheManagerParameterKeys
{

    /**
     * Boolean flag parameter that indicates if the chunked
     * behaviour has been enabled and configured
     *
     * @var string
     */
    const PARAM_KEY_BEHAVIOUR_CHUNKED_ENABLED = "urodoz_cachemanager.chunkedbehaviourenabled";

    /**
     * Parameter key on the container that indicates
     * where the parsed connections of Redis are stored
     *
     * @var string
     */
    const PARAM_KEY_MEMCACHE_CONNECTIONS = "urodoz_cachemanager.memcacheconnections";

    /**
     * Parameter key on the container that indicates
     * where the parsed connections of Redis are stored
     *
     * @var string
     */
    const PARAM_KEY_REDIS_CONNECTIONS = "urodoz_cachemanager.redisconnections";

}
