<?php

namespace SoureCode\Bundle\Unit\Model\Metric\Length;

use SoureCode\Bundle\Unit\Model\Metric\Core\AbstractBaseUnit;

class Meter extends AbstractBaseUnit implements LengthUnitInterface
{
    public static function getSymbol(): string
    {
        return 'm';
    }
}
