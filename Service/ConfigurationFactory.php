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

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Urodoz\Bundle\CacheBundle\DependencyInjection\UrodozCacheExtension;
use Urodoz\Bundle\CacheBundle\DependencyInjection\CacheManagerParameterKeys;
use Urodoz\Bundle\CacheBundle\Exception\BadConfigurationException;
use Symfony\Component\Validator\Validator;

/**
 * Factory used as adapter between the configuration stored
 * on Symfony container and the objects needed for Cachemanager
 * service
 *
 * @author Albert Lacarta <urodoz@gmail.com>
 */
class ConfigurationFactory
{

    /**
     * @var Validator
     */
    private $validator;

    /**
     * Data needed by the factory to build all
     * the supported implementations
     *
     * @var array
     */
    public static $implementationsConfigs = array(
        "memcache" => array(
            "validationStoreClass" => "Urodoz\\Bundle\\CacheBundle\\Service\\Store\\MemcacheConfigurationStore",
            "implementationClass" => "Urodoz\\Bundle\\CacheBundle\\Service\\Implementation\\MemcacheImplementation",
            "parameterKey" => CacheManagerParameterKeys::PARAM_KEY_MEMCACHE_CONNECTIONS,
        ),
        "redis" => array(
            "validationStoreClass" => "Urodoz\\Bundle\\CacheBundle\\Service\\Store\\RedisConfigurationStore",
            "implementationClass" => "Urodoz\Bundle\CacheBundle\Service\Implementation\\RedisImplementation",
            "parameterKey" => CacheManagerParameterKeys::PARAM_KEY_REDIS_CONNECTIONS,
        ),
    );

    /**
     * Class constructor
     *
     * @param Validator $validator
     */
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Factory an implementation adding it to container builder
     * after being validated in structure (not connection) of
     * each server data
     *
     * @param  string                    $implementation
     * @param  array                     $configuration
     * @param  ContainerBuilder          $containerBuilder
     * @throws BadConfigurationException
     */
    public function factoryImplementationConfiguration(
            $implementation,
            array $configuration,
            ContainerBuilder $containerBuilder
            )
    {
        $serversToStore = array();
        if (!in_array($implementation, UrodozCacheExtension::$availableImplementations)) {
            throw new BadConfigurationException(
                    "The implementation {".$implementation."} is not supported by the bundle UrodozCacheManager"
                    );
        }
        $implementationConfigs = static::$implementationsConfigs[$implementation];
        $validationStoreClass = $implementationConfigs["validationStoreClass"];
        $servers = $configuration[$implementation]["servers"];
        foreach ($servers as $serverString) {
            $explodedServerString = explode(":", $serverString);
            if (count($explodedServerString)!=2) {
                throw new BadConfigurationException(
                        "Expected {host}:{port} on server definition, got : " . $serverString
                        );
            }
            //Create the store and validates it
            $storeValidator = new $validationStoreClass($explodedServerString[0], (int) $explodedServerString[1]);
            $violationCollection = $this->validator->validate($storeValidator);
            if (count($violationCollection)!=0) {
                throw new BadConfigurationException(
                        "Error on server definition : "
                        . $serverString
                        . ". "
                        . $violationCollection[0]->getMessage()
                        ." at property {".$violationCollection[0]->getPropertyPath()."}"
                        );
            }

            //Store the server definition on the array to inject on the container
            $serversToStore[] = array(
                "host" => $explodedServerString[0],
                "port" => (int) $explodedServerString[1],
            );
        }

        //Store the response on the container
        $containerBuilder->setParameter($implementationConfigs["parameterKey"], $serversToStore);
    }

}
