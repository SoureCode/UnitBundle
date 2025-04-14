<?php

namespace SoureCode\Bundle\Unit\Form;

use SoureCode\Bundle\Unit\Model\Distance;
use SoureCode\Bundle\Unit\Model\Length\AbstractLengthUnit;
use SoureCode\Bundle\Unit\Model\Length\LengthUnitInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
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

        $builder->addModelTransformer(new CallbackTransformer(
            function (?Distance $data): ?array {
                if (null === $data) {
                    return null;
                }

                $value = $data->getValue();

                return [
                    'value' => (string) $value->getValue(),
                    'unit' => $value::getUnitType(),
                ];
            },
            function (?array $data): ?Distance {
                if (null === $data) {
                    return null;
                }

                if (null === $data['unit'] || null === $data['value']) {
                    return null;
                }

                $unit = AbstractLengthUnit::create($data['value'], $data['unit']);

                return Distance::create($unit);
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

            'default_unit_class' => null,
        ]);

        $resolver->setAllowedValues('default_unit_class', function (?string $value): bool {
            return is_subclass_of($value, LengthUnitInterface::class) || null === $value;
        });
    }

    public function getBlockPrefix(): string
    {
        return 'sourecode_unit_distance';
    }
}
