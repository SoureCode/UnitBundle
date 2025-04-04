<?php

namespace SoureCode\Bundle\Unit\Form;

use SoureCode\Bundle\Unit\Model\Metric\Prefix;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @template-extends AbstractType<string>
 */
class PrefixType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($options) {
                if (null === $event->getData()) {
                    $event->setData($options['default_prefix']->value);
                }
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $keys = array_map(static fn ($case) => $case->value, Prefix::cases());
        $values = array_map(static fn ($case) => $case->name, Prefix::cases());

        $resolver
            ->setDefaults([
                'empty_data' => '',
                'default_prefix' => Prefix::BASE,
                'choices' => array_flip(array_combine($keys, $values)),
                'choice_translation_domain' => 'sourecode_unit_length',
            ]);

        $resolver->setAllowedTypes('default_prefix', [Prefix::class]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'sourecode_unit_prefix';
    }
}
