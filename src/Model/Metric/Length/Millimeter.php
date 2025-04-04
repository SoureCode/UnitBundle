<?php

namespace SoureCode\Bundle\Unit\Model\Metric\Length;

use SoureCode\Bundle\Unit\Model\Metric\Core\AbstractMilliUnit;

class Millimeter extends AbstractMilliUnit implements LengthUnitInterface
{
    public static function getSymbol(): string
    {
        return 'mm';
    }
}
