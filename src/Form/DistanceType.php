<?php

namespace SoureCode\Bundle\Unit\Form;

use SoureCode\Bundle\Unit\Model\Distance;
use SoureCode\Bundle\Unit\Model\Length\AbstractLengthUnit;
use SoureCode\Bundle\Unit\Model\Length\LengthUnitInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @template-extends AbstractType<Distance>
 */
class DistanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('value', ValueType::class, [
                'error_bubbling' => true,
            ])
            ->add('unit', UnitChoiceType::class, [
                'error_bubbling' => true,
                'default_unit_class' => $options['default_unit_class'],
                'unit_class' => AbstractLengthUnit::class,
            ]);

        $builder->addModelTransformer(new DistanceDataTransformer());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'compound' => true,
            'empty_data' => [],
            'by_reference' => false,
            'error_bubbling' => false,

            'default_unit_class' => null,
        ]);

        $resolver->setAllowedValues('default_unit_class', function (?string $value): bool {
            return null === $value || is_subclass_of($value, LengthUnitInterface::class);
        });
    }

    public function getBlockPrefix(): string
    {
        return 'sourecode_unit_distance';
    }
}
