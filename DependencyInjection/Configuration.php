<?php

namespace steevanb\DevBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('dev');

        $rootNode
            ->children()
            ->scalarNode('translation_not_found')->defaultValue(true)->end()
            ->end();

        return $treeBuilder;
    }
}
