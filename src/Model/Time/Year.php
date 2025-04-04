<?php

namespace SoureCode\Bundle\Unit\Model\Time;

use BcMath\Number;

class Year extends AbstractTimeUnit
{
    public static function getUnitType(): string
    {
        return TimeUnitType::YEAR->value;
    }

    public static function getSymbol(): string
    {
        return 'yr';
    }

    public static function getFactor(): Number
    {
        return new Number(365 * 24 * 60 * 60);
    }
}
