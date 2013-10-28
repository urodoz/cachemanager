<?php

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
