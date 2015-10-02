<?php

namespace steevanb\DevBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;
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

        $this->addTranslationConfig($rootNode->children());
        $this->addValidateSchemaConfig($rootNode->children());

        return $treeBuilder;
    }

    /**
     * @param NodeBuilder $node
     */
    protected function addTranslationConfig(NodeBuilder $node)
    {
        $node
            ->scalarNode('translation_not_found')
                ->defaultTrue()
                ->validate()
                ->ifNotInArray(array(true, false))
                    ->thenInvalid('Invalid value %s, boolean required.')
                ->end()
            ->end();
    }

    /**
     * @param NodeBuilder $node
     */
    protected function addValidateSchemaConfig(NodeBuilder $node)
    {
        $node
            ->arrayNode('validate_schema')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('enabled')
                        ->defaultTrue()
                        ->validate()
                        ->ifNotInArray(array(true, false))
                            ->thenInvalid('Invalid value %s, boolean required.')
                        ->end()
                    ->end()
                    ->scalarNode('event')
                        ->defaultValue('kernel.request')
                        ->validate()
                        ->ifNotInArray(array('kernel.request', 'kernel.response'))
                            ->thenInvalid('Invalid value %s, should be kernel.request or kernel.response.')
                        ->end()
                    ->end()
                    ->arrayNode('excludes')
                        ->prototype('scalar')
                    ->end()
                ->end()
            ->end();
    }
}
