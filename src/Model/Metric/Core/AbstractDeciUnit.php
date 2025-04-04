<?php

namespace SoureCode\Bundle\Unit\Model\Metric\Core;

use BcMath\Number;
use SoureCode\Bundle\Unit\Model\Metric\AbstractUnit;
use SoureCode\Bundle\Unit\Model\Metric\Prefix;

abstract class AbstractDeciUnit extends AbstractUnit
{
    public static function getPrefix(): Prefix
    {
        return Prefix::DECI;
    }

    public static function getSymbol(): string
    {
        return 'd';
    }

    public static function getFactor(): Number
    {
        // 1e-1 = 1 / 1e1
        return new Number(1)->div(10 ** 1, 1);
    }
}
