<?php

/*
 * This file is part of the UrodozCacheManager bundle.
 *
 * (c) Albert Lacarta <urodoz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Urodoz\Bundle\CacheBundle\Exception;

/**
 * Exception to be throwed on any fetch from any
 * cache implementation with corrupted data (not matching
 * the hash or not matching the store structure)
 *
 * @author Albert Lacarta <urodoz@gmail.com>
 */
class CorruptedDataException extends CacheException
{

}
