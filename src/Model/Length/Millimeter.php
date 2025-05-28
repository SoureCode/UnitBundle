<?php

namespace SoureCode\Bundle\Unit\Model\Length;

use BcMath\Number;

class Millimeter extends AbstractLengthUnit
{
    public static function getUnitType(): string
    {
        return LengthUnitType::MILLIMETER->value;
    }

    public static function getSymbol(): string
    {
        return 'mm';
    }

    public static function getFactor(): Number
    {
        // 1e-3 = 1 / 1e3
        $base = new Number(1);

        return $base->div(10 ** 3, 3);
    }
}
