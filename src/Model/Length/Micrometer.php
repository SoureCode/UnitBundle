<?php

namespace SoureCode\Bundle\Unit\Model\Length;

use BcMath\Number;

class Micrometer extends AbstractLengthUnit
{
    public static function getUnitType(): string
    {
        return LengthUnitType::MICROMETER->value;
    }

    public static function getSymbol(): string
    {
        return 'μm';
    }

    public static function getFactor(): Number
    {
        // 1e-6 = 1 / 1e6
        $base = new Number(1);

        return $base->div(10 ** 6, 6);
    }
}
