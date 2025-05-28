<?php

namespace SoureCode\Bundle\Unit\Converter;

use BcMath\Number;
use SoureCode\Bundle\Unit\Model\UnitInterface;

/**
 * @template T of UnitInterface
 */
abstract class AbstractConverter
{
    /**
     * @phpstan-param T $base
     * @phpstan-param class-string<T> $unitClass
     *
     * @phpstan-return T
     */
    protected static function doConvert(UnitInterface $base, string $unitClass): UnitInterface
    {
        if ($base::class === $unitClass) {
            return clone $base;
        }

        $valueFactor = $base::getFactor();
        $targetFactor = $unitClass::getFactor();

        $value = $base->getValue();

        $baseValue = self::fixTrailingZeros($value->mul($valueFactor));

        if (0 === $targetFactor->compare(new Number(0))) {
            throw new \LogicException('Target factor cannot be zero.');
        }

        $convertedValue = self::fixTrailingZeros($baseValue->div($targetFactor));

        return new $unitClass($convertedValue);
    }

    public static function fixTrailingZeros(Number $number): Number
    {
        if (str_contains($number->value, '.')) {
            return new Number(rtrim(rtrim($number->value, '0'), '.'));
        }

        return $number;
    }
}
