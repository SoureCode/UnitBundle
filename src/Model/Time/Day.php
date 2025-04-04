<?php

namespace SoureCode\Bundle\Unit\Model\Time;

use BcMath\Number;

class Day extends AbstractTimeUnit
{
    public static function getUnitType(): string
    {
        return TimeUnitType::DAY->value;
    }

    public static function getSymbol(): string
    {
        return 'd';
    }

    public static function getFactor(): Number
    {
        return new Number(24 * 60 * 60);
    }
}
