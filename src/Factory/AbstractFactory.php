<?php

namespace SoureCode\Bundle\Unit\Factory;

use BcMath\Number;
use SoureCode\Bundle\Unit\Model\UnitInterface;

/**
 * @template T of UnitInterface
 */
class AbstractFactory
{
    /**
     * @phpstan-param class-string<T> $className
     *
     * @phpstan-return T
     */
    protected static function doCreate(Number|string|int|float $value, string $className): UnitInterface
    {
        return new $className($value);
    }
}
