<?php

namespace SoureCode\Bundle\Unit;

use SoureCode\Bundle\Unit\Doctrine\DBAL\Types\LengthType;
use SoureCode\Bundle\Unit\Model\Metric\Converter;
use SoureCode\Bundle\Unit\Model\Metric\Factory;
use SoureCode\Bundle\Unit\Model\Metric\Normalizer;
use SoureCode\Bundle\Unit\Model\Metric\Prefix;
use SoureCode\Bundle\Unit\Model\Metric\UnitInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

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
                ->arrayNode('units')
                    ->useAttributeAsKey('name')
                    ->arrayPrototype()
                        ->children()
                            ->arrayNode('mapping')
                                ->useAttributeAsKey('prefix')
                                ->scalarPrototype()
                                    ->validate()
                                        ->ifTrue(fn ($v) => !class_exists($v) || !is_subclass_of($v, UnitInterface::class))
                                        ->thenInvalid('The class "%s" is not a valid unit class must implement "'.UnitInterface::class.'".')
                                    ->end()
                                ->end()
                                ->validate()
                                    ->ifTrue(fn ($v) => \count(array_diff(array_keys($v), array_map(static fn ($v) => $v->value, Prefix::cases()))) > 0)
                                    ->thenInvalid('The mapping "%s" is not valid.')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
        // @formatter:on
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $parameters = $container->parameters();
        $services = $container->services();

        /**
         * @var list<string> $unitNames
         */
        $unitNames = array_keys($config['units']);

        foreach ($unitNames as $unitName) {
            $mapping = $config['units'][$unitName]['mapping'] ?? [];

            $parameters->set(self::$PREFIX.$unitName.'.mapping', $mapping);

            $services
                ->set(self::$PREFIX.$unitName.'.converter', Converter::class)
                ->args([
                    param(self::$PREFIX.$unitName.'.mapping'),
                ])
                ->public();

            $services
                ->set(self::$PREFIX.$unitName.'.factory', Factory::class)
                ->args([
                    param(self::$PREFIX.$unitName.'.mapping'),
                ])
                ->public();

            $services
                ->set(self::$PREFIX.$unitName.'.normalizer', Normalizer::class)
                ->args([
                    service(self::$PREFIX.$unitName.'.converter'),
                    param(self::$PREFIX.$unitName.'.mapping'),
                ])
                ->public();
        }

        if (\in_array('length', $unitNames, true) && class_exists(AbstractType::class)) {
            $services
                ->set(self::$PREFIX.'form.length_type', Form\LengthType::class)
                ->args([
                    param(self::$PREFIX.'length.mapping'),
                    service(self::$PREFIX.'length.converter'),
                ])
                ->tag('form.type');
        }
    }

    public function boot(): void
    {
        if ($this->container->has(self::$PREFIX.'length.converter')) {
            LengthType::$converter = $this->container->get(static::$PREFIX.'length.converter');
            LengthType::$factory = $this->container->get(static::$PREFIX.'length.factory');
            LengthType::$normalizer = $this->container->get(static::$PREFIX.'length.normalizer');
        }
    }

    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/packages/*.php');
    }
}
