<?php

namespace FileAnalyzer\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('file_analyzer');

        $rootNode
            ->children()
                ->scalarNode('services_file_path')->end()
                ->arrayNode('from_path')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('kind')->end()
                            ->scalarNode('in_path')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
