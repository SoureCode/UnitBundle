<?php

namespace SoureCode\Bundle\Unit\Converter;

use SoureCode\Bundle\Unit\Model\Time\AbstractTimeUnit;
use SoureCode\Bundle\Unit\Model\Time\TimeUnitInterface;

/**
 * @extends AbstractConverter<TimeUnitInterface>
 */
class TimeConverter extends AbstractConverter
{
    /**
     * @template U of TimeUnitInterface
     *
     * @phpstan-param U $base
     * @phpstan-param class-string<U> $unitClass
     *
     * @phpstan-return U
     */
    public static function convert(TimeUnitInterface $base, string $unitClass): TimeUnitInterface
    {
        if (!\in_array($unitClass, AbstractTimeUnit::CLASSES, true)) {
            throw new \InvalidArgumentException(\sprintf("Conversion from '%s' to '%s' is not supported.", $base::class, $unitClass));
        }

        /*
         * @phpstan-ignore-next-line
         */
        return parent::doConvert($base, $unitClass);
    }
}
