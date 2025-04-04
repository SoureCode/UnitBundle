<?php

namespace SoureCode\Bundle\Unit\Form;

use SoureCode\Bundle\Unit\Model\Metric\Core\AbstractPicoUnit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @template-extends AbstractType<string>
 */
class ValueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->resetViewTransformers();

        $builder->addViewTransformer(new CallbackTransformer(
            function ($value) {
                if (null === $value) {
                    return '';
                }

                if (!is_numeric($value)) {
                    throw new TransformationFailedException('Expected a numeric.');
                }

                return (float) $value;
            },
            function ($value) {
                if (null === $value || '' === $value) {
                    return null;
                }

                return (float) $value;
            },
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'html5' => true,
            'grouping' => false,
            'scale' => AbstractPicoUnit::getFactor()->scale,
            'input' => 'string',
            'empty_data' => '',
        ]);
    }

    public function getParent(): string
    {
        return NumberType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'sourecode_unit_value';
    }
}
