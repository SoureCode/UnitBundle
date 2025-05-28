<?php

namespace SoureCode\Bundle\Unit\Model\Time;

use SoureCode\Bundle\Unit\Converter\TimeConverter;
use SoureCode\Bundle\Unit\Model\AbstractUnit;
use SoureCode\Bundle\Unit\Normalizer\TimeNormalizer;

abstract class AbstractTimeUnit extends AbstractUnit implements TimeUnitInterface
{
    /**
     * @var list<class-string<TimeUnitInterface>>
     */
    public const array CLASSES = [
        Year::class,
        Month::class,
        Week::class,
        Day::class,
        Hour::class,
        Minute::class,
        Second::class,
        Millisecond::class,
        Microsecond::class,
        Nanosecond::class,
        Picosecond::class,
    ];

    /**
     * @return list<TimeUnitType>
     */
    public static function getCases(): array
    {
        return TimeUnitType::cases();
    }

    /**
     * @phpstan-template T of TimeUnitInterface
     *
     * @phpstan-param class-string<T> $className
     *
     * @phpstan-return T
     */
    public function convert(string $className): TimeUnitInterface
    {
        /**
         * @var T $value
         */
        $value = TimeConverter::convert($this, $className);

        return $value;
    }

    public function normalize(): TimeUnitInterface
    {
        return TimeNormalizer::normalize($this);
    }
}
