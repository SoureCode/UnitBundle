<?php

namespace SoureCode\Bundle\Unit\Model\Length;

use BcMath\Number;

class Decameter extends AbstractLengthUnit
{
    public static function getUnitType(): string
    {
        return LengthUnitType::DECAMETER->value;
    }

    public static function getSymbol(): string
    {
        return 'dam';
    }

    public static function getFactor(): Number
    {
        return new Number(10 ** 1);
    }
}
