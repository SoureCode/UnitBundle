<?php

namespace SoureCode\Bundle\Unit\Form;

use SoureCode\Bundle\Unit\Model\Metric\ConverterInterface;
use SoureCode\Bundle\Unit\Model\Metric\FactoryInterface;
use SoureCode\Bundle\Unit\Model\Metric\Length\LengthUnitInterface;
use SoureCode\Bundle\Unit\Model\Metric\Prefix;
use SoureCode\Bundle\Unit\Model\Metric\UnitInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @template-extends AbstractType<UnitInterface>
 */
class LengthType extends AbstractType
{
    public function __construct(
        /**
         * @var array<class-string<Prefix>, class-string<LengthUnitInterface>> $mapping
         */
        private readonly array $mapping = [],
        /**
         * @var FactoryInterface<LengthUnitInterface>|null
         */
        private readonly ?FactoryInterface $factory = null,
        /**
         * @var ConverterInterface<LengthUnitInterface>|null
         */
        private readonly ?ConverterInterface $converter = null,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('value', ValueType::class, [
                'error_bubbling' => true,
            ])
            ->add('prefix', PrefixType::class, [
                'error_bubbling' => true,
                'default_prefix' => $options['default_prefix'],
                'choice_filter' => function ($choiceValue) {
                    return isset($this->mapping[$choiceValue]);
                },
            ]);

        $builder->addModelTransformer(new CallbackTransformer(
            function (?LengthUnitInterface $data): ?array {
                if (null === $data) {
                    return null;
                }

                return [
                    'value' => (string) $data->getValue(),
                    'prefix' => $data::getPrefix()->value,
                ];
            },
            function (?array $data) use ($options): ?LengthUnitInterface {
                if (null === $data) {
                    return null;
                }

                $prefix = $data['prefix'];
                $value = $data['value'];

                if (null === $prefix || null === $value) {
                    return null;
                }

                $unit = $this->factory->create($value, Prefix::from($prefix));

                $targetPrefix = $options['target_prefix'];

                if (null !== $targetPrefix) {
                    return $this->converter->convert($unit, $targetPrefix);
                }

                return $unit;
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'compound' => true,
            'empty_data' => [],
            'by_reference' => false,
            'error_bubbling' => false,
            'default_prefix' => Prefix::BASE,
            'target_prefix' => null,
        ]);

        $resolver->setAllowedTypes('default_prefix', [Prefix::class]);
        $resolver->setAllowedTypes('target_prefix', [Prefix::class, 'null']);
    }

    public function getBlockPrefix(): string
    {
        return 'sourecode_unit_length';
    }
}
