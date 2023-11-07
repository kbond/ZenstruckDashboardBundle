<?php

namespace Zenstruck\Bundle\DashboardBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Zenstruck\Bundle\DashboardBundle\DependencyInjection\Compiler\ServiceCompilerPass;

class ZenstruckDashboardBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new ServiceCompilerPass());
    }
}
