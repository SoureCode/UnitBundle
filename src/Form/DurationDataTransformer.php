<?php

namespace SoureCode\Bundle\Unit\Form;

use BcMath\Number;
use SoureCode\Bundle\Unit\Model\Duration;
use SoureCode\Bundle\Unit\Model\Time\Day;
use SoureCode\Bundle\Unit\Model\Time\Hour;
use SoureCode\Bundle\Unit\Model\Time\Minute;
use SoureCode\Bundle\Unit\Model\Time\Month;
use SoureCode\Bundle\Unit\Model\Time\Second;
use SoureCode\Bundle\Unit\Model\Time\TimeUnitType;
use SoureCode\Bundle\Unit\Model\Time\Year;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * @template-implements DataTransformerInterface<?Duration, array{year: ?string, month: ?string, day: ?string, hour: ?string, minute: ?string, second: ?string}>
 */
class DurationDataTransformer implements DataTransformerInterface
{
    public function __construct(
        /**
         * @var array{year: bool, month: bool, day: bool, hour: bool, minute: bool, second: bool} $options
         */
        private readonly array $options,
    ) {
    }

    /**
     * @param Duration|null $value
     *
     * @return array{year: ?string, month: ?string, day: ?string, hour: ?string, minute: ?string, second: ?string}|null
     */
    public function transform(mixed $value): ?array
    {
        if (null === $value) {
            return null;
        }

        /**
         * @var array{year: int, month: int, day: int, hour: int, minute: int, second: int} $decomposed
         */
        $decomposed = $value->decompose();

        /**
         * @var array{year: ?Duration, month: ?Duration, day: ?Duration, hour: ?Duration, minute: ?Duration, second: ?Duration} $decomposedValues
         */
        $decomposedValues = [
            'year' => null,
            'month' => null,
            'day' => null,
            'hour' => null,
            'minute' => null,
            'second' => null,
        ];

        /**
         * Accumulated keeps track of decomposedValues inside disabled view fields.
         */
        $accumulated = Duration::zero();
        $lastEnabledKey = null;

        foreach ($decomposed as $key => $unitValue) {
            $value = TimeUnitType::fromString($key)->create($unitValue);

            if ($this->options[$key]) {
                $lastEnabledKey = $key;
                $decomposedValues[$key] = $accumulated->add($value);
                $accumulated = Duration::zero();
            } else {
                // If disabled, add the current value to the accumulated state.
                $accumulated = $accumulated->add($value);
            }
        }

        if (null !== $lastEnabledKey && $accumulated->getValue()->getValue()->compare(new Number(0)) > 0) {
            $decomposedValues[$lastEnabledKey] = $decomposedValues[$lastEnabledKey]?->add($accumulated);
        }

        /**
         * @var array{year: ?string, month: ?string, day: ?string, hour: ?string, minute: ?string, second: ?string} $result
         */
        $result = [
            'year' => null,
            'month' => null,
            'day' => null,
            'hour' => null,
            'minute' => null,
            'second' => null,
        ];

        foreach ($decomposedValues as $key => $decomposedValue) {
            $result[$key] = $this->stringify($key, $decomposedValue);
        }

        return $result;
    }

    private function stringify(string $key, ?Duration $duration): ?string
    {
        if (null === $duration) {
            return null;
        }

        $className = TimeUnitType::fromString($key)->toClassName();

        return (string) $duration->getValue()->convert($className)->getValue();
    }

    /**
     * Converts the data from the form to the model.
     */
    public function reverseTransform(mixed $value): ?Duration
    {
        if (null === $value) {
            return null;
        }

        if (array_all($value, fn ($item) => null === $item)) {
            return null;
        }

        $distance = Duration::zero();
        $distance = $distance->add(new Year($value['year'] ?? 0));
        $distance = $distance->add(new Month($value['month'] ?? 0));
        $distance = $distance->add(new Day($value['day'] ?? 0));
        $distance = $distance->add(new Hour($value['hour'] ?? 0));
        $distance = $distance->add(new Minute($value['minute'] ?? 0));

        return $distance->add(new Second($value['second'] ?? 0));
    }
}
