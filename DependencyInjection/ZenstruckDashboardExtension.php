<?php

namespace Zenstruck\Bundle\DashboardBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class ZenstruckDashboardExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('zenstruck_dashboard.config', $config);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('dashboard.xml');

        if ($menuService = $config['menu_service']) {
            $managerDefinition = $container->getDefinition('zenstruck_dashboard.manager');

            $managerDefinition->addMethodCall('setMenu', array(new Reference($menuService)));
        }

        if ($config['user_service']) {
            $loader->load('user_service.xml');
        }
    }
}
