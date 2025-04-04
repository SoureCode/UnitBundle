<?php

namespace SoureCode\Bundle\Unit\Model\Metric\Length;

use SoureCode\Bundle\Unit\Model\Metric\Core\AbstractHectoUnit;

class Hectometer extends AbstractHectoUnit implements LengthUnitInterface
{
    public static function getSymbol(): string
    {
        return 'hm';
    }
}
