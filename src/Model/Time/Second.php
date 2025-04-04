<?php

namespace SoureCode\Bundle\Unit\Model\Time;

use BcMath\Number;

class Second extends AbstractTimeUnit
{
    public static function getUnitType(): string
    {
        return TimeUnitType::SECOND->value;
    }

    public static function getSymbol(): string
    {
        return 's';
    }

    public static function getFactor(): Number
    {
        return new Number(1);
    }
}
