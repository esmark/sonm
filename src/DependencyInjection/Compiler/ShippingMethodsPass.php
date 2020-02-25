<?php

declare(strict_types=1);

namespace App\DependencyInjection\Compiler;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Reference;

class ShippingMethodsPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $taggedServices = $container->findTaggedServiceIds('app.shipping.method');

        $data = [];

        foreach ($taggedServices as $taggedService => $_dummy) {
            $sc = new Reference(ContainerInterface::class);
            $em = new Reference(EntityManagerInterface::class);

            $definition = $container->getDefinition($taggedService);
            $definition
                ->setPublic(true)
                ->addMethodCall('setContainer', [$sc])
                ->addMethodCall('setEntityManager', [$em])
            ;


            $data[$taggedService] = $definition->getClass();
        }

        $container->setParameter('app.shipping.methods', $data);
    }
}
