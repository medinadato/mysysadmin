<?php

namespace MDN\AdminBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use MDN\AdminBundle\DependencyInjection\Compiler\DoctrineEntityListenerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MDNAdminBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

//        $container->addCompilerPass(new DoctrineEntityListenerPass());
    }
}
