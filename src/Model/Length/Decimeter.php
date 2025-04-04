<?php

namespace SoureCode\Bundle\Unit\Model\Length;

use BcMath\Number;

class Decimeter extends AbstractLengthUnit
{
    public static function getUnitType(): string
    {
        return LengthUnitType::DECIMETER->value;
    }

    public static function getSymbol(): string
    {
        return 'dm';
    }

    public static function getFactor(): Number
    {
        // 1e-1 = 1 / 1e1
        return new Number(1)->div(10 ** 1, 1);
    }
}
