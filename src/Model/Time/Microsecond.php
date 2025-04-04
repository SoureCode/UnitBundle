<?php

namespace SoureCode\Bundle\Unit\Model\Time;

use BcMath\Number;

class Microsecond extends AbstractTimeUnit
{
    public static function getUnitType(): string
    {
        return TimeUnitType::MICROSECOND->value;
    }

    public static function getSymbol(): string
    {
        return 'µs';
    }

    public static function getFactor(): Number
    {
        return new Number(1)->div(10 ** 6, 6);
    }
}
