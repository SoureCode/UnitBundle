<?php

namespace SoureCode\Bundle\Unit\Model\Metric\Core;

use BcMath\Number;
use SoureCode\Bundle\Unit\Model\Metric\AbstractUnit;
use SoureCode\Bundle\Unit\Model\Metric\Prefix;

abstract class AbstractGigaUnit extends AbstractUnit
{
    public static function getPrefix(): Prefix
    {
        return Prefix::GIGA;
    }

    public static function getSymbol(): string
    {
        return 'G';
    }

    public static function getFactor(): Number
    {
        return new Number(10 ** 9);
    }
}
