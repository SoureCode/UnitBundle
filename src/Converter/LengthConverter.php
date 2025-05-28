<?php

namespace SoureCode\Bundle\Unit\Converter;

use SoureCode\Bundle\Unit\Model\Length\AbstractLengthUnit;
use SoureCode\Bundle\Unit\Model\Length\LengthUnitInterface;

/**
 * @extends AbstractConverter<LengthUnitInterface>
 */
class LengthConverter extends AbstractConverter
{
    /**
     * @template T of LengthUnitInterface
     *
     * @phpstan-param T $base
     * @phpstan-param class-string<T> $unitClass
     *
     * @phpstan-return T
     */
    public static function convert(LengthUnitInterface $base, string $unitClass): LengthUnitInterface
    {
        if (!\in_array($unitClass, AbstractLengthUnit::CLASSES, true)) {
            throw new \InvalidArgumentException(\sprintf("Conversion from '%s' to '%s' is not supported.", $base::class, $unitClass));
        }

        /*
         * @phpstan-ignore-next-line
         */
        return parent::doConvert($base, $unitClass);
    }
}
