<?php

namespace SoureCode\Bundle\Unit\Model\Length;

use SoureCode\Bundle\Unit\Model\AbstractUnit;
use SoureCode\Bundle\Unit\Model\UnitInterface;

abstract class AbstractLengthUnit extends AbstractUnit implements LengthUnitInterface
{
    public static function getCases(): array
    {
        return LengthUnitType::cases();
    }

    public static function getMapping(): array
    {
        static $cachedMapping = null;

        if (null === $cachedMapping) {
            /**
             * @var class-string<UnitInterface>[] $values
             */
            $values = [
                Kilometer::class,
                Hectometer::class,
                Decameter::class,
                Meter::class,
                Decimeter::class,
                Centimeter::class,
                Millimeter::class,
                Micrometer::class,
                Nanometer::class,
                Picometer::class,
            ];

            $keys = array_map(static fn ($value) => $value::getUnitType(), $values);
            $cachedMapping = array_combine($keys, $values);
        }

        return $cachedMapping;
    }
}
