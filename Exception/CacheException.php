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

class CacheException extends \Exception
{

    /**
     * {@inheritDoc}
     */
    public function __construct($message, $code=null, $previous=null)
    {
        parent::__construct($message, $code, $previous);
    }

}
