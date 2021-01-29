<?php

namespace Kiboko\Plugin\Spreadsheet\Configuration;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Extractor implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder = new TreeBuilder('extractor');

        $builder->getRootNode()
            ->children()
                ->scalarNode('file_path')->isRequired()->end()
                ->scalarNode('sheet')->defaultValue(',')->end()
                ->scalarNode('skip_lines')->defaultValue(',')->end()
                ->scalarNode('delimiter')->defaultValue(',')->end()
                ->scalarNode('enclosure')->defaultValue('"')->end()
                ->scalarNode('escape')->defaultValue('\\')->end()
            ->end();

        return $builder;
    }
}
