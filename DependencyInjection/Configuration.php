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
            ->end()
        ;

        return $treeBuilder;
    }
}
