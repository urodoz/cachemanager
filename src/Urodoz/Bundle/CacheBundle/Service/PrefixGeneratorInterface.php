<?php

/*
 * This file is part of the UrodozCacheManager bundle.
 *
 * (c) Albert Lacarta <urodoz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Urodoz\Bundle\CacheBundle\Service;

/**
 * Interface used by CacheManager service
 * to generate the prefix key by any service class
 *
 * @author Albert Lacarta <urodoz@gmail.com>
 */
interface PrefixGeneratorInterface
{

    /**
     * Returns the prefix as string
     *
     * @param  string $nonPrefixedKey
     * @return string
     */
    public function getPrefix($nonPrefixedKey);

}
