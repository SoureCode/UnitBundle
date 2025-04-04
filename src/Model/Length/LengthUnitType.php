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

    public static function getChoices(): array
    {
        $keys = array_map(
            static fn (LengthUnitType $prefix): string => $prefix->value,
            self::cases()
        );

        $values = array_map(
            static fn (string $key): string => LengthUnitType::from($key)->name,
            $keys,
        );

        return array_flip(array_combine($keys, $values));
    }
}
