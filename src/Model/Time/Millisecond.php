<?php

namespace SoureCode\Bundle\Unit\Model\Time;

use BcMath\Number;

class Millisecond extends AbstractTimeUnit
{
    public static function getUnitType(): string
    {
        return TimeUnitType::MILLISECOND->value;
    }

    public static function getSymbol(): string
    {
        return 'ms';
    }

    public static function getFactor(): Number
    {
        // 1e-3 = 1 / 1e3
        return new Number(1)->div(10 ** 3, 3);
    }
}
