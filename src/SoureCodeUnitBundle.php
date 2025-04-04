<?php

namespace SoureCode\Bundle\Unit;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

final class SoureCodeUnitBundle extends AbstractBundle
{
    protected static string $PREFIX = 'soure_code.unit.';

    public function configure(DefinitionConfigurator $definition): void
    {
        /**
         * @var ArrayNodeDefinition $rootNode
         */
        $rootNode = $definition->rootNode();

        // @formatter:off
        $rootNode
            ->fixXmlConfig('unit')
            ->children()
            // @todo :|
            ->end()
        ;
        // @formatter:on
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $parameters = $container->parameters();
        $services = $container->services();
    }

    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/packages/*.php');
    }
}
