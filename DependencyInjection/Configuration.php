<?php

namespace FDevs\BowerPhpBundle\DependencyInjection;

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
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('f_devs_bower_php');

        $rootNode
            ->children()
                ->scalarNode('cache_dir')->defaultValue('%kernel.cache_dir%/bowerphp')->end()
                ->scalarNode('install_dir')->defaultValue('%kernel.root_dir%/../web/components')->end()
                ->scalarNode('bower_path')->defaultValue('%kernel.root_dir%/config/bower.json')->end()
                ->scalarNode('github_token')->defaultNull()->end()
            ->end();

        return $treeBuilder;
    }
}
