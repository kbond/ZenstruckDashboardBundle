<?php

namespace Zenstruck\Bundle\DashboardBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ServiceCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('zenstruck_dashboard.manager')) {
            return;
        }

        $definition = $container->getDefinition('zenstruck_dashboard.manager');

        $taggedServices = $container->findTaggedServiceIds(
            'zenstruck_dashboard.service'
        );

        foreach ($taggedServices as $id => $attributes) {
            if (!isset($attributes[0]['alias'])) {
                throw new InvalidArgumentException(sprintf('Service "%s" must have an alias set.', $id));
            }

            $definition->addMethodCall('registerService', array($attributes[0]['alias'], new Reference($id)));
        }
    }
}
