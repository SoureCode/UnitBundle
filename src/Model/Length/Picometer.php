<?php

namespace SoureCode\Bundle\Unit\Model\Length;

use BcMath\Number;

class Picometer extends AbstractLengthUnit
{
    public static function getUnitType(): string
    {
        return LengthUnitType::PICOMETER->value;
    }

    public static function getSymbol(): string
    {
        return 'pm';
    }

    public static function getFactor(): Number
    {
        // 1e-12 = 1 / 1e12
        return new Number(1)->div(10 ** 12, 12);
    }
}
