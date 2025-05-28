<?php

namespace SoureCode\Bundle\Unit\Form;

use SoureCode\Bundle\Unit\Model\Duration;
use SoureCode\Bundle\Unit\Model\Time\Day;
use SoureCode\Bundle\Unit\Model\Time\Hour;
use SoureCode\Bundle\Unit\Model\Time\Minute;
use SoureCode\Bundle\Unit\Model\Time\Month;
use SoureCode\Bundle\Unit\Model\Time\Second;
use SoureCode\Bundle\Unit\Model\Time\Year;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @template-extends AbstractType<Duration>
 */
class DurationType extends AbstractType
{
    /**
     * @param array{year: bool, month: bool, day: bool, hour: bool, minute: bool, second: bool} $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addValueField('year', $builder, $options);
        $this->addValueField('month', $builder, $options);
        $this->addValueField('day', $builder, $options);
        $this->addValueField('hour', $builder, $options);
        $this->addValueField('minute', $builder, $options);
        $this->addValueField('second', $builder, $options);

        $builder->addModelTransformer(new DurationDataTransformer($options));
    }

    /**
     * @phpstan-param FormBuilderInterface<Duration|null> $builder
     *
     * @param array{year: bool, month: bool, day: bool, hour: bool, minute: bool, second: bool} $options
     */
    private function addValueField(string $unit, FormBuilderInterface $builder, array $options): void
    {
        if (!$options[$unit]) {
            return;
        }

        $builder
            ->add($unit, ValueType::class, [
                'error_bubbling' => true,
                'label' => $unit,
                'attr' => [
                    'placeholder' => $unit,
                    'autocomplete' => 'off',
                ],
            ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['units'] = [
            'year' => $options['year'],
            'month' => $options['month'],
            'day' => $options['day'],
            'hour' => $options['hour'],
            'minute' => $options['minute'],
            'second' => $options['second'],
        ];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'compound' => true,
            'empty_data' => [],
            'by_reference' => false,
            'error_bubbling' => false,

            'year' => false,
            'month' => false,
            'day' => false,
            'hour' => true,
            'minute' => true,
            'second' => false,
        ]);

        $resolver->setAllowedTypes('year', 'bool');
        $resolver->setAllowedTypes('month', 'bool');
        $resolver->setAllowedTypes('day', 'bool');
        $resolver->setAllowedTypes('hour', 'bool');
        $resolver->setAllowedTypes('minute', 'bool');
        $resolver->setAllowedTypes('second', 'bool');

        $resolver->setNormalizer('year', function (Options $options, $value) {
            if (
                !$value
                && !$options['month']
                && !$options['day']
                && !$options['hour']
                && !$options['minute']
                && !$options['second']
            ) {
                throw new \InvalidArgumentException('At least one of the options (year, month, day, hour, minute, second) must be enabled.');
            }

            return $value;
        });

        $resolver->setNormalizer('empty_data', function (Options $options, $value) {
            $data = [];

            $fields = ['year', 'month', 'day', 'hour', 'minute', 'second'];

            foreach ($fields as $field) {
                if (isset($options[$field]) && $options[$field]) {
                    $data[$field] = 0;
                }
            }

            return $data;
        });
    }

    public function getBlockPrefix(): string
    {
        return 'sourecode_unit_duration';
    }
}
