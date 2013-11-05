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

use Urodoz\Bundle\CacheBundle\Service\Implementation\CacheImplementationInterface;
use Urodoz\Bundle\CacheBundle\Exception\CacheException;
use Urodoz\Bundle\CacheBundle\Service\ConfigurationFactory;
use Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher;
use Urodoz\Bundle\CacheBundle\Event\CacheHitEvent;
use Urodoz\Bundle\CacheBundle\Event\MissedCacheHitEvent;
use Urodoz\Bundle\CacheBundle\Event\UpdateCacheKeyEvent;
use Urodoz\Bundle\CacheBundle\Event\EventStore;
use Urodoz\Bundle\CacheBundle\Exception\CorruptedDataException;
use Urodoz\Bundle\CacheBundle\Service\CacheItem\CacheItemStore;

class CacheManager implements CacheImplementationInterface
{

    /**
     * Array of connections
     *
     * @var array
     */
    private $connections=array();

    /**
     * Array of implementations
     *
     * @var array
     */
    private $implementations=array();

    /**
     * Determines the active implementation
     * on the cacheManager
     *
     * @var string
     */
    private $activeImplementation=null;

    /**
     * Event dispatcher
     *
     * @var ContainerAwareEventDispatcher
     */
    private $eventDispatcher;

    public function setGenericConnections($key, array $connections)
    {
        if(!isset($this->connections[$key])) $this->connections[$key] = array();
        foreach ($connections as $configConnection) {
            $this->connections[$key][] = $configConnection;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return "CacheManager";
    }

    /**
     * Sets the event dispatcher on the cache manager
     *
     * @param ContainerAwareEventDispatcher $eventDispatcher
     */
    public function setEventDispatcher(ContainerAwareEventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    private function raiseExceptionIfNoImplementationSetted()
    {
        if (is_null($this->activeImplementation)) {
            throw new CacheException(
                    "No implementation has been loaded to manage cache. "
                    . "Maybe you forgot to call the implementation method "
                    . "first : \$cacheManager->implementation()->set(..."
                    );
        }
    }

    /**
     * @return CacheManager
     */
    public function implementation($key)
    {
        //Check connections
        if (!isset($this->connections[$key]) || empty($this->connections[$key])) {
            throw new \Exception("Cannot connect to {".$key."} implementation. None defined on configuration");
        }
        //Create implementation with connections
        if (!isset($this->implementations[$key])) {
            $implementation = $this->factoryImplementation($key);
            $implementation->init($this->connections[$key]);
            $this->implementations[$key] = $implementation;
        }

        $this->activeImplementation = $key;

        return $this;
    }

    /**
     * @return CacheImplementationInterface
     */
    private function factoryImplementation($key)
    {
        $configuration = ConfigurationFactory::$implementationsConfigs[$key];
        $implementationClass = $configuration["implementationClass"];
        if (!class_exists($implementationClass)) {
            throw new \Exception("Class not found {".$implementationClass."}");
        }
        $implementationInstance = new $implementationClass();

        return $implementationInstance;
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
        //Creating and dispatching the event
        $event = new UpdateCacheKeyEvent($cacheKey);
        $this->eventDispatcher->dispatch(EventStore::UPDATE_CACHE_KEY, $event);
        //Returning updated key
        return $event->getKey();
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

    /**
     * Returns the active implementation of CacheManager
     *
     * @return CacheImplementationInterface
     */
    public function getActiveImplementation()
    {
        $this->raiseExceptionIfNoImplementationSetted();

        return $this->implementations[$this->activeImplementation];
    }

    /*
     * Interface implementation as Driver to final implementation
     */

    /**
     * {@inheritDoc}
     */
    public function init(array $connections)
    {
        $this->raiseExceptionIfNoImplementationSetted();
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value, $timeout=3600)
    {
        $key = $this->updateCacheKey($key);

        //Create the unit to be stored
        $cacheItemStore = new CacheItemStore();
        $cacheItemStore->createFromData($value, $timeout);
        $valueToBeCached = $cacheItemStore->getCacheData();

        $result = $this->getActiveImplementation()->set($key, $valueToBeCached, $timeout);

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function get($key)
    {
        $key = $this->updateCacheKey($key);
        $result = $this->getActiveImplementation()->get($key);

        //Parse result to retrieve it raw
        if ($result) {
            try {
                $cacheItemStore = new CacheItemStore();
                $cacheItemStore->hydrateFromCacheData($result);
                $cacheItemStore->getData();
                $result = $cacheItemStore->getData();
            } catch (CorruptedDataException $e) {
                $result = null;
            }
        }

        //Throwing event
        if ($result) {
            //Dispatch memcache hit event
            $event = new CacheHitEvent($this->getActiveImplementation()->getName(), $key, $result);
            $this->eventDispatcher->dispatch("urodoz.events.cachehit", $event);
        } else {
            //Dispatch memcache missed hit event
            $event = new MissedCacheHitEvent($this->getActiveImplementation()->getName(), $key);
            $this->eventDispatcher->dispatch("urodoz.events.missed_cachehit", $event);
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function setIndexed($key, $value, $timeout=null)
    {
        $key = $this->updateCacheKey($key);

        return $this->getActiveImplementation()->setIndexed($key, $value, $timeout);
    }

    /**
     * {@inheritDoc}
     */
    public function getIndexed($key)
    {
        $key = $this->updateCacheKey($key);

        return $this->getActiveImplementation()->getIndexed($key);
    }

    /**
     * {@inheritDoc}
     */
    public function has($key)
    {
        $key = $this->updateCacheKey($key);

        return $this->getActiveImplementation()->has($key);
    }

    /**
     * {@inheritDoc}
     */
    public function hasIndexed($key)
    {
        $key = $this->updateCacheKey($key);

        return $this->getActiveImplementation()->hasIndexed($key);
    }

    /**
     * {@inheritDoc}
     */
    public function remove($key)
    {
        $key = $this->updateCacheKey($key);

        return $this->getActiveImplementation()->remove($key);
    }

    /**
     * {@inheritDoc}
     */
    public function removeIndexed($pattern)
    {
        $key = $this->updateCacheKey($key);

        return $this->getActiveImplementation()->removeIndexed($pattern);
    }

}
