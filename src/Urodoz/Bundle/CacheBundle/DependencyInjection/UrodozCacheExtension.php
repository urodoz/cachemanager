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

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator;
use Urodoz\Bundle\CacheBundle\Service\ConfigurationFactory;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 *
 * @author Albert Lacarta <urodoz@gmail.com>
 */
class UrodozCacheExtension extends Extension
{

    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var ConfigurationFactory
     */
    private $configurationFactory;

    /**
     * Array of supported implementations for cache
     * management
     *
     * @var array
     */
    public static $availableImplementations=array("memcache", "redis");

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $environment = $container->getParameter("kernel.environment");
        //Load needed helpers
        $this->loadSymfonyValidator();
        $this->loadConfigurationFactory();

        /*
         * Load Implementations
         */
        foreach (static::$availableImplementations as $implementation) {
            if (isset($config[$implementation]["servers"])) {
                $this->configurationFactory->factoryImplementationConfiguration(
                        $implementation,
                        $config,
                        $container
                        );
            }

        }

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        //Loading dev services if dev or test
        if ($environment=="dev" || $environment=="test") {
            $loader->load('services_dev.yml');
        }
    }

    /**
     * Create a symfony validator to validate the
     * configuration params and stores the validator
     * instance of the Extension
     *
     * @return void
     */
    private function loadSymfonyValidator()
    {
        $this->validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
    }

    /**
     * Create an instance of the configuration factory
     * and add a reference from the Extension
     *
     * @return void
     */
    private function loadConfigurationFactory()
    {
        $this->configurationFactory = new ConfigurationFactory($this->validator);
    }

}
