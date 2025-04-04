<?php

namespace SoureCode\Bundle\Unit\Model\Metric\Core;

use BcMath\Number;
use SoureCode\Bundle\Unit\Model\Metric\AbstractUnit;
use SoureCode\Bundle\Unit\Model\Metric\Prefix;

abstract class AbstractDecaUnit extends AbstractUnit
{
    public static function getPrefix(): Prefix
    {
        return Prefix::DECA;
    }

    public static function getSymbol(): string
    {
        return 'da';
    }

    public static function getFactor(): Number
    {
        return new Number(10 ** 1);
    }
}
