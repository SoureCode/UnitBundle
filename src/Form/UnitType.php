<?php

namespace SoureCode\Bundle\Unit\Form;

use SoureCode\Bundle\Unit\Model\Length\AbstractLengthUnit;
use SoureCode\Bundle\Unit\Model\UnitInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @template-extends AbstractType<UnitInterface>
 */
class UnitType extends AbstractType
{
    public function __construct()
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('value', ValueType::class, [
                'error_bubbling' => true,
            ])
            ->add('unit_type', UnitTypeType::class, [
                'error_bubbling' => true,
                'default_unit_type' => $options['default_unit_type'],
                'unit_class' => $options['unit_class'],
                'choice_filter' => function ($choiceValue) use ($options) {
                    return isset($options['unit_class']::getMapping()[$choiceValue]);
                },
            ]);

        $builder->addModelTransformer(new CallbackTransformer(
            function (?UnitInterface $data): ?array {
                if (null === $data) {
                    return null;
                }

                return [
                    'value' => (string) $data->getValue(),
                    'unit_type' => $data::getUnitType(),
                ];
            },
            function (?array $data) use ($options): ?UnitInterface {
                if (null === $data) {
                    return null;
                }

                $unitType = $data['unit_type'];
                $value = $data['value'];

                if (null === $unitType || null === $value) {
                    return null;
                }

                /**
                 * @var class-string<UnitInterface> $unitClass
                 */
                $unitClass = $options['unit_class'];

                $unit = $unitClass::create($value, $unitType);

                /**
                 * @var class-string<UnitInterface> $targetUnitClass
                 */
                $targetUnitClass = $options['target_unit_class'];

                if (null !== $targetUnitClass) {
                    $unit = $unit->convert($targetUnitClass);
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

            'unit_class' => AbstractLengthUnit::class,
            'default_unit_type' => null,
            'target_unit_class' => null,
        ]);

        $resolver->setAllowedValues('unit_class', function (?string $value): bool {
            if (null === $value) {
                return false;
            }

            return is_subclass_of($value, UnitInterface::class);
        });

        $resolver->setAllowedValues('default_unit_type', function (?string $value): bool {
            return is_subclass_of($value, UnitInterface::class) || null === $value;
        });

        $resolver->setAllowedValues('target_unit_class', function (?string $value): bool {
            return is_subclass_of($value, UnitInterface::class) || null === $value;
        });
    }

    public function getBlockPrefix(): string
    {
        return 'sourecode_unit_length';
    }
}
