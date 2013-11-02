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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('urodoz_cache');

        $rootNode
                ->children()
                    ->arrayNode("memcache")
                        ->children()
                            ->arrayNode("servers")
                                ->prototype('scalar')
                                ->end()
                            ->end()
                        ->end()
                    ->end()

                    ->arrayNode("redis")
                        ->children()
                            ->arrayNode("servers")
                                ->prototype('scalar')
                                ->end()
                            ->end()
                        ->end()
                    ->end()

                    /*
                     * Behaviours
                     */
                    ->arrayNode("behaviours")
                        ->children()
                            ->arrayNode("chunked_items")
                                ->children()
                                    ->scalarNode("chunk_size")
                                    ->end()
                                    ->booleanNode("enabled")
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()

                ->end();

        return $treeBuilder;
    }
}
