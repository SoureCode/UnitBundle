<?php

namespace SoureCode\Bundle\Unit\Factory;

use BcMath\Number;
use SoureCode\Bundle\Unit\Model\Time\AbstractTimeUnit;
use SoureCode\Bundle\Unit\Model\Time\TimeUnitInterface;

/**
 * @template-extends AbstractFactory<TimeUnitInterface>
 */
class TimeFactory extends AbstractFactory
{
    /**
     * @template T of TimeUnitInterface
     *
     * @phpstan-param class-string<T> $className
     *
     * @phpstan-return T
     */
    public static function create(Number|string|int|float $value, string $className): TimeUnitInterface
    {
        if (!\in_array($className, AbstractTimeUnit::CLASSES, true)) {
            throw new \InvalidArgumentException(\sprintf('Class "%s" is not a valid time unit.', $className));
        }

        /*
         * @phpstan-ignore-next-line
         */
        return parent::doCreate($value, $className);
    }
}
