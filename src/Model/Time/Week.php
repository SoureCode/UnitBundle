<?php

namespace SoureCode\Bundle\Unit\Model\Time;

use BcMath\Number;

class Week extends AbstractTimeUnit
{
    public static function getUnitType(): string
    {
        return TimeUnitType::WEEK->value;
    }

    public static function getSymbol(): string
    {
        return 'wk';
    }

    public static function getFactor(): Number
    {
        return new Number(7 * 24 * 60 * 60);
    }
}
