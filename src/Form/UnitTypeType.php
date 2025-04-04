<?php

namespace SoureCode\Bundle\Unit\Form;

use SoureCode\Bundle\Unit\Model\Length\AbstractLengthUnit;
use SoureCode\Bundle\Unit\Model\UnitInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @template-extends AbstractType<string>
 */
class UnitTypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($options) {
                if (null === $event->getData()) {
                    $event->setData($options['default_unit_type']);
                }
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $choices = static function (Options $options) {
            /**
             * @var class-string<UnitInterface> $unitType
             */
            $unitType = $options['unit_class'];

            return $unitType::getChoices();
        };

        $resolver
            ->setDefaults([
                'empty_data' => '',
                'choices' => $choices,
                'choice_translation_domain' => 'sourecode_unit_type',

                'unit_class' => AbstractLengthUnit::class,
                'default_unit_type' => null,
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
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'sourecode_unit_unit_type';
    }
}
