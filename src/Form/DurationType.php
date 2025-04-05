<?php

namespace SoureCode\Bundle\Unit\Form;

use BcMath\Number;
use SoureCode\Bundle\Unit\Model\Duration;
use SoureCode\Bundle\Unit\Model\Time\AbstractTimeUnit;
use SoureCode\Bundle\Unit\Model\Time\Day;
use SoureCode\Bundle\Unit\Model\Time\Hour;
use SoureCode\Bundle\Unit\Model\Time\Minute;
use SoureCode\Bundle\Unit\Model\Time\Month;
use SoureCode\Bundle\Unit\Model\Time\Second;
use SoureCode\Bundle\Unit\Model\Time\Year;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
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
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addValueField('year', $builder, $options);
        $this->addValueField('month', $builder, $options);
        $this->addValueField('day', $builder, $options);
        $this->addValueField('hour', $builder, $options);
        $this->addValueField('minute', $builder, $options);
        $this->addValueField('second', $builder, $options);

        $builder->addModelTransformer(new CallbackTransformer(
            function (?Duration $data) use ($options): ?array {
                if (null === $data) {
                    return null;
                }

                $decomposed = $data->decompose();

                $values = [
                    'year' => null,
                    'month' => null,
                    'day' => null,
                    'hour' => null,
                    'minute' => null,
                    'second' => null,
                ];

                $accumulated = Duration::zero();
                $lastEnabledKey = null;

                foreach ($decomposed as $pluralKey => $unitValue) {
                    $key = rtrim($pluralKey, 's');

                    if ($options[$key]) {
                        $lastEnabledKey = $key;
                        $values[$key] = $accumulated->add(AbstractTimeUnit::create($unitValue, $key));
                        $accumulated = Duration::zero();
                    } else {
                        // If disabled, add the current value to the accumulated state.
                        $accumulated = $accumulated->add(AbstractTimeUnit::create($unitValue, $key));
                    }
                }

                if ($accumulated->getValue()->getValue()->compare(new Number(0)) > 0) {
                    $values[$lastEnabledKey] = $values[$lastEnabledKey]->add($accumulated);
                }

                $keys = array_keys($values);

                return array_combine($keys, array_map(static function (?Duration $duration, string $key) {
                    if (null === $duration) {
                        return null;
                    }

                    return (string) $duration->getValue()->convert($key)->getValue();
                }, $values, $keys));
            },
            function (?array $data): ?Duration {
                if (null === $data) {
                    return null;
                }

                if (array_all($data, fn ($value) => null === $value)) {
                    return null;
                }

                $distance = Duration::zero();
                $distance = $distance->add(new Year($data['year'] ?? 0));
                $distance = $distance->add(new Month($data['month'] ?? 0));
                $distance = $distance->add(new Day($data['day'] ?? 0));
                $distance = $distance->add(new Hour($data['hour'] ?? 0));
                $distance = $distance->add(new Minute($data['minute'] ?? 0));
                $distance = $distance->add(new Second($data['second'] ?? 0));

                return $distance;
            }
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
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
            'second' => true,
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
    }

    public function getBlockPrefix(): string
    {
        return 'sourecode_unit_duration';
    }

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
}
