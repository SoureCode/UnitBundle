<?php

namespace SoureCode\Bundle\Unit\Model\Length;

use SoureCode\Bundle\Unit\Converter\LengthConverter;
use SoureCode\Bundle\Unit\Model\AbstractUnit;
use SoureCode\Bundle\Unit\Normalizer\LengthNormalizer;

abstract class AbstractLengthUnit extends AbstractUnit implements LengthUnitInterface
{
    /**
     * @var list<class-string<LengthUnitInterface>>
     */
    public const array CLASSES = [
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

    /**
     * @return list<LengthUnitType>
     */
    public static function getCases(): array
    {
        return LengthUnitType::cases();
    }

    /**
     * @phpstan-template T of LengthUnitInterface
     *
     * @phpstan-param class-string<T> $className
     *
     * @phpstan-return T
     */
    public function convert(string $className): LengthUnitInterface
    {
        /**
         * @var T $value
         */
        $value = LengthConverter::convert($this, $className);

        return $value;
    }

    public function normalize(): LengthUnitInterface
    {
        return LengthNormalizer::normalize($this);
    }
}
