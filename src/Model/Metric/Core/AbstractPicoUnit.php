<?php

namespace SoureCode\Bundle\Unit\Model\Metric\Core;

use BcMath\Number;
use SoureCode\Bundle\Unit\Model\Metric\AbstractUnit;
use SoureCode\Bundle\Unit\Model\Metric\Prefix;

abstract class AbstractPicoUnit extends AbstractUnit
{
    public static function getPrefix(): Prefix
    {
        return Prefix::PICO;
    }

    public static function getSymbol(): string
    {
        return 'p';
    }

    public static function getFactor(): Number
    {
        // 1e-12 = 1 / 1e12
        return new Number(1)->div(10 ** 12, 12);
    }
}
