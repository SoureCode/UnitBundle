<?php

namespace SoureCode\Bundle\Unit\Model\Time;

use BcMath\Number;

class Minute extends AbstractTimeUnit
{
    public static function getUnitType(): string
    {
        return TimeUnitType::MINUTE->value;
    }

    public static function getSymbol(): string
    {
        return 'min';
    }

    public static function getFactor(): Number
    {
        return new Number(60);
    }
}
