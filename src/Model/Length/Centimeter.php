<?php

namespace SoureCode\Bundle\Unit\Model\Length;

use BcMath\Number;

class Centimeter extends AbstractLengthUnit
{
    public static function getUnitType(): string
    {
        return LengthUnitType::CENTIMETER->value;
    }

    public static function getSymbol(): string
    {
        return 'cm';
    }

    public static function getFactor(): Number
    {
        // 1e-2 = 1 / 1e2
        return new Number(1)->div(10 ** 2, 2);
    }
}
