<?php

namespace Zenstruck\Bundle\DashboardBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('zenstruck_dashboard');

        $rootNode
            ->children()
                ->scalarNode('title')->defaultValue('Administration')->end()
                ->scalarNode('layout')->defaultValue('ZenstruckDashboardBundle:Twitter:layout.html.twig')->end()
                ->arrayNode('widgets')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('title')->defaultNull()->end()
                            ->scalarNode('controller')->defaultNull()->end()
                            ->scalarNode('route')->defaultNull()->end()
                            ->booleanNode('ajax')->defaultFalse()->end()
                            ->scalarNode('group')->defaultNull()->end()
                            ->variableNode('params')->defaultValue(array())->end()
                        ->end()
                        ->validate()
                            ->ifTrue(function($v) { return !($v['controller'] || $v['route']); })
                            ->thenInvalid('You must specify either a controller or a route.')
                            ->ifTrue(function($v) { return ($v['ajax'] && !$v['route']); })
                            ->thenInvalid('When using an ajax widget, a route must be set.')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('menu')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('label')->defaultNull()->end()
                            ->scalarNode('group')->defaultValue('primary')->end()
                            ->scalarNode('icon')->defaultNull()->end()
                            ->booleanNode('nested')->defaultTrue()->end()
                            ->arrayNode('items')
                                ->useAttributeAsKey('name')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('label')->defaultNull()->end()
                                        ->scalarNode('uri')->defaultNull()->end()
                                        ->scalarNode('route')->defaultNull()->end()
                                        ->variableNode('routeParameters')->end()
                                        ->scalarNode('role')->defaultNull()->end()
                                        ->scalarNode('icon')->defaultNull()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
