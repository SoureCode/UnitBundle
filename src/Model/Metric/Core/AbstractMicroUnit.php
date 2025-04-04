<?php

namespace SoureCode\Bundle\Unit\Model\Metric\Core;

use BcMath\Number;
use SoureCode\Bundle\Unit\Model\Metric\AbstractUnit;
use SoureCode\Bundle\Unit\Model\Metric\Prefix;

abstract class AbstractMicroUnit extends AbstractUnit
{
    public static function getPrefix(): Prefix
    {
        return Prefix::MICRO;
    }

    public static function getSymbol(): string
    {
        return 'μ';
    }

    public static function getFactor(): Number
    {
        // 1e-6 = 1 / 1e6
        return new Number(1)->div(10 ** 6, 6);
    }
}
