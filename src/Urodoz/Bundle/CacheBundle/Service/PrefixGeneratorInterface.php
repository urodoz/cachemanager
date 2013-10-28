<?php

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
