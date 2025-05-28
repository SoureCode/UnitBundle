<?php

namespace SoureCode\Bundle\Unit\Factory;

use BcMath\Number;
use SoureCode\Bundle\Unit\Model\Length\AbstractLengthUnit;
use SoureCode\Bundle\Unit\Model\Length\LengthUnitInterface;

/**
 * @template-extends AbstractFactory<LengthUnitInterface>
 */
class LengthFactory extends AbstractFactory
{
    /**
     * @template T of LengthUnitInterface
     *
     * @phpstan-param class-string<T> $className
     *
     * @phpstan-return T
     */
    public static function create(Number|string|int|float $value, string $className): LengthUnitInterface
    {
        if (!\in_array($className, AbstractLengthUnit::CLASSES, true)) {
            throw new \InvalidArgumentException(\sprintf('Class "%s" is not a valid length unit.', $className));
        }

        /*
         * @phpstan-ignore-next-line
         */
        return parent::doCreate($value, $className);
    }
}
