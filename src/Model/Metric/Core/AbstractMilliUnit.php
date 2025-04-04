<?php

namespace SoureCode\Bundle\Unit\Model\Metric\Core;

use BcMath\Number;
use SoureCode\Bundle\Unit\Model\Metric\AbstractUnit;
use SoureCode\Bundle\Unit\Model\Metric\Prefix;

abstract class AbstractMilliUnit extends AbstractUnit
{
    public static function getPrefix(): Prefix
    {
        return Prefix::MILLI;
    }

    public static function getSymbol(): string
    {
        return 'm';
    }

    public static function getFactor(): Number
    {
        // 1e-3 = 1 / 1e3
        return new Number(1)->div(10 ** 3, 3);
    }
}
