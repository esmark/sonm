<?php

declare(strict_types=1);

namespace App\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class PaymentMethodsPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $taggedServices = $container->findTaggedServiceIds('app.payment.method');

        $data = [];

        foreach ($taggedServices as $taggedService => $_dummy) {
            $definition = $container->getDefinition($taggedService);
            $definition->setPublic(true);

            $data[$taggedService] = $definition->getClass();
        }

        $container->setParameter('app.payment.methods', $data);
    }
}
