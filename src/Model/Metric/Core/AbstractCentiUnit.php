<?php

namespace SoureCode\Bundle\Unit\Model\Metric\Core;

use BcMath\Number;
use SoureCode\Bundle\Unit\Model\Metric\AbstractUnit;
use SoureCode\Bundle\Unit\Model\Metric\Prefix;

abstract class AbstractCentiUnit extends AbstractUnit
{
    public static function getPrefix(): Prefix
    {
        return Prefix::CENTI;
    }

    public static function getSymbol(): string
    {
        return 'c';
    }

    public static function getFactor(): Number
    {
        // 1e-2 = 1 / 1e2
        return new Number(1)->div(10 ** 2, 2);
    }
}
