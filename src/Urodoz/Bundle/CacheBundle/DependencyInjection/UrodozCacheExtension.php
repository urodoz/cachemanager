<?php

namespace Urodoz\Bundle\CacheBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Urodoz\Bundle\CacheBundle\Service\Store\MemcacheConfigurationStore;
use Urodoz\Bundle\CacheBundle\Service\Store\RedisConfigurationStore;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class UrodozCacheExtension extends Extension
{

    const PARAM_KEY_MEMCACHE_IMPLEMENTATIONS = "urodoz_cachemanager.memcacheimplementations";
    const PARAM_KEY_REDIS_IMPLEMENTATIONS = "urodoz_cachemanager.redisimplementations";

    /**
     * @var Validator
     */
    private $validator;

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        //Load cache implementations
        if (isset($config["memcache"]["servers"])) {
            $this->loadMemcacheServers($config["memcache"]["servers"],$container);
        } else {
            //Default array of servers : Empty
            $container->setParameter(static::PARAM_KEY_MEMCACHE_IMPLEMENTATIONS, array());
        }

        if (isset($config["redis"]["servers"])) {
            $this->loadRedisServers($config["redis"]["servers"],$container);
        } else {
            //Default array of servers : Empty
            $container->setParameter(static::PARAM_KEY_REDIS_IMPLEMENTATIONS, array());
        }

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * Create a symfony validator to validate the
     * configuration params
     *
     * @return Validator
     */
    private function getSymfonyValidator()
    {
        if (!$this->validator) {
            $this->validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
        }

        return $this->validator;
    }

    /**
     * Generates the store objects and adds it as configuration parameter
     * to container for Redis connections
     *
     * @param array            $serversFromConfiguration
     * @param ContainerBuilder $container
     */
    private function loadRedisServers(array $serversFromConfiguration, ContainerBuilder $container)
    {
        $serverArray = array();
        $validator = $this->getSymfonyValidator();
        foreach ($serversFromConfiguration as $serverItem) {
            $explodedConf = explode(":", $serverItem);
            if (count($explodedConf)!=2) {
                throw new \Exception("Error parsing redis server : ".$serverItem.", expected {host}:{port} (example : 127.0.0.1:6379)");
            }
            $host = $explodedConf[0];
            $port = $explodedConf[1];

            $serverConfiguration = new RedisConfigurationStore($host, $port);
            $violations = $validator->validate($validator);
            if (count($violations)>0) {
                throw new \Exception("Error validating server : ".$serverItem.", " . $violations->getMessage()." at path {".$violations->getPropertyPath()."}");
            }

            //Adding configuration
            $serverArray[] = $serverItem;
        }

        //Setting up value on the container builder
        $container->setParameter(static::PARAM_KEY_REDIS_IMPLEMENTATIONS, $serverArray);
    }

    /**
     * Generates the store objects and adds it as configuration parameter
     * to container for memcache connections
     *
     * @param array            $serversFromConfiguration
     * @param ContainerBuilder $container
     */
    private function loadMemcacheServers(array $serversFromConfiguration, ContainerBuilder $container)
    {
        $serverArray = array();
        $validator = $this->getSymfonyValidator();
        foreach ($serversFromConfiguration as $serverItem) {
            $explodedConf = explode(":", $serverItem);
            if (count($explodedConf)!=2) {
                throw new \Exception("Error parsing memcache server : ".$serverItem.", expected {host}:{port} (example : 127.0.0.1:11211)");
            }
            $host = $explodedConf[0];
            $port = $explodedConf[1];

            $serverConfiguration = new MemcacheConfigurationStore($host, $port);
            $violations = $validator->validate($validator);
            if (count($violations)>0) {
                throw new \Exception("Error validating server : ".$serverItem.", " . $violations->getMessage()." at path {".$violations->getPropertyPath()."}");
            }

            //Adding configuration
            $serverArray[] = $serverItem;
        }

        //Setting up value on the container builder
        $container->setParameter(static::PARAM_KEY_MEMCACHE_IMPLEMENTATIONS, $serverArray);
    }
}
