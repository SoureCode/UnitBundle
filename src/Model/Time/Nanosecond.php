<?php

namespace SoureCode\Bundle\Unit\Model\Time;

use BcMath\Number;

class Nanosecond extends AbstractTimeUnit
{
    public static function getUnitType(): string
    {
        return TimeUnitType::NANOSECOND->value;
    }

    public static function getSymbol(): string
    {
        return 'ns';
    }

    public static function getFactor(): Number
    {
        return new Number(1)->div(10 ** 9, 9);
    }
}
