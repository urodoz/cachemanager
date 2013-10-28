<?php

namespace Urodoz\Bundle\CacheBundle\Service;

use Urodoz\Bundle\CacheBundle\Service\PrefixGeneratorInterface;

abstract class AbstractCacheImplementation
{

    /**
     * Service to generate prefix for cache keys
     *
     * @var PrefixGeneratorInterface
     */
    protected $prefixGenerator;

    /**
     * Class constructor
     *
     * @param PrefixGeneratorInterface $prefixGenerator
     */
    public function __construct(PrefixGeneratorInterface $prefixGenerator = null)
    {
        if(!is_null($prefixGenerator)) $this->prefixGenerator = $prefixGenerator;
    }

    /**
     * Apply all modifications to cacheKey before being
     * used on the implementation
     *
     * @param  string $cacheKey
     * @return string
     */
    protected function updateCacheKey($cacheKey)
    {
        if($this->hasPrefixGenerator()) $cacheKey = $this->prefixGenerator->getPrefix ($cacheKey) . $cacheKey;

        return $cacheKey;
    }

    /**
     * Boolean flag indicator for a Cache implementation
     * containing a service for prefix generator
     *
     * @return boolean
     */
    protected function hasPrefixGenerator()
    {
        return ($this->prefixGenerator instanceof PrefixGeneratorInterface);
    }

}
