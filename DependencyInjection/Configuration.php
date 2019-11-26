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

        $widgetContentTypes = array('route', 'controller', 'template');
        $widgetIncludeTypes = array('embed', 'hinclude', 'esi', 'ajax');

        $rootNode
            ->children()
                ->booleanNode('user_service')->defaultFalse()->end()
                ->scalarNode('title')->defaultValue('Administration')->end()
                ->scalarNode('theme')->defaultValue('@ZenstruckDashboard/Bootstrap2')->end()
                ->variableNode('theme_options')->defaultValue(array())->end()
                ->scalarNode('dashboard_template')->defaultNull()->end()
                ->scalarNode('layout')->defaultNull()->end()
                ->scalarNode('menu_service')->defaultNull()->end()
                ->arrayNode('widgets')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('title')->defaultNull()->end()
                            ->scalarNode('content')->isRequired()->end()
                            ->scalarNode('content_type')
                                ->defaultValue('controller')
                                ->info(implode(', ', $widgetContentTypes))
                                ->validate()
                                    ->ifNotInArray($widgetContentTypes)
                                    ->thenInvalid('Content type %s is not supported. Please choose one of '.json_encode($widgetContentTypes))
                                ->end()
                            ->end()
                            ->scalarNode('include_type')
                                ->defaultValue('embed')
                                ->info(implode(', ', $widgetIncludeTypes))
                                ->validate()
                                    ->ifNotInArray($widgetIncludeTypes)
                                    ->thenInvalid('Include type %s is not supported. Please choose one of '.json_encode($widgetIncludeTypes))
                                ->end()
                            ->end()
                            ->scalarNode('group')->defaultNull()->end()
                            ->scalarNode('role')->defaultNull()->end()
                            ->variableNode('params')->defaultValue(array())->end()
                        ->end()
                        ->validate()
                            ->ifTrue(function ($v) { return 'ajax' === $v['include_type'] && 'route' !== $v['content_type']; })
                            ->thenInvalid('include_type ajax requires content_type route')
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
