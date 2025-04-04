<?php

namespace SoureCode\Bundle\Unit\Model\Length;

use BcMath\Number;

class Meter extends AbstractLengthUnit
{
    public static function getUnitType(): string
    {
        return LengthUnitType::METER->value;
    }

    public static function getSymbol(): string
    {
        return 'm';
    }

    public static function getFactor(): Number
    {
        return new Number(1);
    }
}
