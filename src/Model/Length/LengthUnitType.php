<?php

namespace SoureCode\Bundle\Unit\Model\Length;

enum LengthUnitType: string
{
    case KILOMETER = 'kilometer';
    case HECTOMETER = 'hectometer';
    case DECAMETER = 'decameter';
    case METER = 'meter';
    case DECIMETER = 'decimeter';
    case CENTIMETER = 'centimeter';
    case MILLIMETER = 'millimeter';
    case MICROMETER = 'micrometer';
    case NANOMETER = 'nanometer';
    case PICOMETER = 'picometer';

    /**
     * @return class-string<LengthUnitInterface>
     */
    public function toClassName(): string
    {
        return match ($this) {
            self::KILOMETER => Kilometer::class,
            self::HECTOMETER => Hectometer::class,
            self::DECAMETER => Decameter::class,
            self::METER => Meter::class,
            self::DECIMETER => Decimeter::class,
            self::CENTIMETER => Centimeter::class,
            self::MILLIMETER => Millimeter::class,
            self::MICROMETER => Micrometer::class,
            self::NANOMETER => Nanometer::class,
            self::PICOMETER => Picometer::class,
        };
    }
}
