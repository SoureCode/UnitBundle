<?php

namespace SoureCode\Bundle\Unit\Model\Metric\Length;

use SoureCode\Bundle\Unit\Model\Metric\Core\AbstractDeciUnit;

class Decimeter extends AbstractDeciUnit implements LengthUnitInterface
{
    public static function getSymbol(): string
    {
        return 'dm';
    }
}
