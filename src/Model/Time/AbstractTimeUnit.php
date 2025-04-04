<?php

namespace SoureCode\Bundle\Unit\Model\Time;

use SoureCode\Bundle\Unit\Model\AbstractUnit;
use SoureCode\Bundle\Unit\Model\UnitInterface;

abstract class AbstractTimeUnit extends AbstractUnit implements TimeUnitInterface
{
    public static function getCases(): array
    {
        return TimeUnitType::cases();
    }

    public static function getMapping(): array
    {
        static $cachedMapping = null;

        if (null === $cachedMapping) {
            /**
             * @var class-string<UnitInterface>[] $values
             */
            $values = [
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

            $keys = array_map(static fn ($value) => $value::getUnitType(), $values);
            $cachedMapping = array_combine($keys, $values);
        }

        return $cachedMapping;
    }
}
