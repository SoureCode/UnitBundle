<?php

namespace SoureCode\Bundle\Unit\Model\Metric;

enum Prefix: string
{
    case GIGA = 'giga';
    case MEGA = 'mega';
    case KILO = 'kilo';
    case HECTO = 'hecto';
    case DECA = 'deca';
    case BASE = 'base';
    case DECI = 'deci';
    case CENTI = 'centi';
    case MILLI = 'milli';
    case MICRO = 'micro';
    case NANO = 'nano';
    case PICO = 'pico';

    public static function getChoices(): array
    {
        $keys = array_map(
            static fn (Prefix $prefix): string => $prefix->value,
            self::cases()
        );

        $values = array_map(
            static fn (string $key): string => Prefix::from($key)->name,
            $keys,
        );

        return array_flip(array_combine($keys, $values));
    }
}
