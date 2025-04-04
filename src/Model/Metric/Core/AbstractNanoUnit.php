<?php

namespace SoureCode\Bundle\Unit\Model\Metric\Core;

use BcMath\Number;
use SoureCode\Bundle\Unit\Model\Metric\AbstractUnit;
use SoureCode\Bundle\Unit\Model\Metric\Prefix;

abstract class AbstractNanoUnit extends AbstractUnit
{
    public static function getPrefix(): Prefix
    {
        return Prefix::NANO;
    }

    public static function getSymbol(): string
    {
        return 'n';
    }

    public static function getFactor(): Number
    {
        // 1e-9 = 1 / 1e9
        return new Number(1)->div(10 ** 9, 9);
    }
}
