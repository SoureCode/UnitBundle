<?php

namespace SoureCode\Bundle\Unit\Model\Time;

use BcMath\Number;

class Hour extends AbstractTimeUnit
{
    public static function getUnitType(): string
    {
        return TimeUnitType::HOUR->value;
    }

    public static function getSymbol(): string
    {
        return 'h';
    }

    public static function getFactor(): Number
    {
        return new Number(60 * 60);
    }
}
