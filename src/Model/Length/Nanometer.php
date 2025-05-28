<?php

namespace SoureCode\Bundle\Unit\Model\Length;

use BcMath\Number;

class Nanometer extends AbstractLengthUnit
{
    public static function getUnitType(): string
    {
        return LengthUnitType::NANOMETER->value;
    }

    public static function getSymbol(): string
    {
        return 'nm';
    }

    public static function getFactor(): Number
    {
        // 1e-9 = 1 / 1e9
        $base = new Number(1);

        return $base->div(10 ** 9, 9);
    }
}
