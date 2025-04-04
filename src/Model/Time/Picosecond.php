<?php

namespace SoureCode\Bundle\Unit\Model\Time;

use BcMath\Number;

class Picosecond extends AbstractTimeUnit
{
    public static function getUnitType(): string
    {
        return TimeUnitType::PICOSECOND->value;
    }

    public static function getSymbol(): string
    {
        return 'ps';
    }

    public static function getFactor(): Number
    {
        return new Number(1)->div(10 ** 12, 12);
    }
}
