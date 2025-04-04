<?php

namespace SoureCode\Bundle\Unit\Model\Metric;

use BcMath\Number;

/**
 * @template T of UnitInterface
 *
 * @template-implements ConverterInterface<T>
 */
class Converter implements ConverterInterface
{
    public function __construct(
        /**
         * @var array<string, class-string<T>> $mapping
         */
        private array $mapping = [],
    ) {
    }

    public function convert(UnitInterface $unit, Prefix $target): UnitInterface
    {
        if ($unit::getPrefix() === $target) {
            return $unit;
        }

        $valueFactor = $unit::getFactor();
        $targetUnitClass = $this->mapping[$target->value] ?? null;

        if (null === $targetUnitClass) {
            throw new \InvalidArgumentException(\sprintf("Conversion from '%s' to '%s' is not supported.", $unit::class, $target->value));
        }

        $targetFactor = $targetUnitClass::getFactor();

        $value = $unit->getValue();
        $baseValue = AbstractUnit::fixTrailingZeros($value->mul($valueFactor));

        if (0 === $targetFactor->compare(new Number(0))) {
            throw new \LogicException('Target factor cannot be zero.');
        }

        $convertedValue = AbstractUnit::fixTrailingZeros($baseValue->div($targetFactor));

        return new $targetUnitClass($convertedValue);
    }
}
