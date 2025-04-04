<?php

namespace SoureCode\Bundle\Unit\Model\Time;

use BcMath\Number;

class Month extends AbstractTimeUnit
{
    public static function getUnitType(): string
    {
        return TimeUnitType::MONTH->value;
    }

    public static function getSymbol(): string
    {
        return 'mo';
    }

    public static function getFactor(): Number
    {
        return new Number(30 * 24 * 60 * 60);
    }
}
