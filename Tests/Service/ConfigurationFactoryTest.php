<?php

/*
 * This file is part of the UrodozCacheManager bundle.
 *
 * (c) Albert Lacarta <urodoz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Urodoz\Bundle\CacheBundle\Tests\Service;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Urodoz\Bundle\CacheBundle\Service\ConfigurationFactory;
use Urodoz\Bundle\CacheBundle\Exception\BadConfigurationException;
use Urodoz\Bundle\CacheBundle\Lib\UrodozBaseTest;

/**
 * @code
 * phpunit -v -c app/ vendor/urodoz/cachemanager/Urodoz/Bundle/CacheBundle/Tests/Service/ConfigurationFactoryTest.php
 * @endcode
 */
class ConfigurationFactoryTest extends UrodozBaseTest
{

    /**
     * @code
     * phpunit -v --filter testExceptionOnNonExistantImplementation -c app/ vendor/urodoz/cachemanager/Urodoz/Bundle/CacheBundle/Tests/Service/ConfigurationFactoryTest.php
     * @endcode
     * @expectedException Urodoz\Bundle\CacheBundle\Exception\BadConfigurationException
     */
    public function testExceptionOnNonExistantImplementation()
    {
        $container = $this->buildAndMarkSkippedCacheServersUndefined();
        $confFactory = new ConfigurationFactory($container->get("validator"));
        $confFactory->factoryImplementationConfiguration(
                "Fake_Implementation",
                array(
                    "Fake_Implementation" => array(
                        "servers" => array("127.0.0.1:12345")
                    ),
                ),
                new ContainerBuilder()
                );
    }

    /**
     * @code
     * phpunit -v --filter testBadServerDefinitionException -c app/ vendor/urodoz/cachemanager/Urodoz/Bundle/CacheBundle/Tests/Service/ConfigurationFactoryTest.php
     * @endcode
     * @expectedException Urodoz\Bundle\CacheBundle\Exception\BadConfigurationException
     */
    public function testBadServerDefinitionException()
    {
        $container = $this->buildAndMarkSkippedCacheServersUndefined();
        $confFactory = new ConfigurationFactory($container->get("validator"));
        $confFactory->factoryImplementationConfiguration(
                "memcache",
                array(
                    "memcache" => array(
                        "servers" => array("127.0.0.1:12345:12")
                    ),
                ),
                new ContainerBuilder()
                );
    }

    /**
     * Provides data for the test testBadValidationOfServerDefinitionValues
     *
     * @return array
     */
    public function providerBadServerDefinitions()
    {
        return array(
            array("127.0.0.1::11211"),
            array(":11211"),
            array("127.0.0.1"),
            array(127001),
            array("67.34.12.999:11211"),
            array("localhost:11211"),
            array("127.0.0.1:ABC"),
        );
    }

    /**
     * @code
     * phpunit -v --filter testBadValidationOfServerDefinitionValues -c app/ vendor/urodoz/cachemanager/Urodoz/Bundle/CacheBundle/Tests/Service/ConfigurationFactoryTest.php
     * @endcode
     * @dataProvider providerBadServerDefinitions
     * @expectedException Urodoz\Bundle\CacheBundle\Exception\BadConfigurationException
     */
    public function testBadValidationOfServerDefinitionValues($serverDefinition)
    {
        $container = $this->buildAndMarkSkippedCacheServersUndefined();
        $confFactory = new ConfigurationFactory($container->get("validator"));
        $confFactory->factoryImplementationConfiguration(
                "memcache",
                array(
                    "memcache" => array(
                        "servers" => array($serverDefinition)
                    ),
                ),
                new ContainerBuilder()
                );
    }

}
