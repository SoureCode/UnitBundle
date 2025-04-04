<?php

namespace SoureCode\Bundle\Unit\Model\Length;

use BcMath\Number;

class Hectometer extends AbstractLengthUnit
{
    public static function getUnitType(): string
    {
        return LengthUnitType::HECTOMETER->value;
    }

    public static function getSymbol(): string
    {
        return 'hm';
    }

    public static function getFactor(): Number
    {
        return new Number(10 ** 2);
    }
}
