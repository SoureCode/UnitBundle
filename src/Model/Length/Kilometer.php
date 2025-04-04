<?php

namespace SoureCode\Bundle\Unit\Model\Length;

use BcMath\Number;

class Kilometer extends AbstractLengthUnit
{
    public static function getSymbol(): string
    {
        return 'km';
    }

    public static function getUnitType(): string
    {
        return LengthUnitType::KILOMETER->value;
    }

    public static function getFactor(): Number
    {
        return new Number(10 ** 3);
    }
}
