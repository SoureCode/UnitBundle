<?php

namespace SoureCode\Bundle\Unit\Model\Metric\Length;

use SoureCode\Bundle\Unit\Model\Metric\Core\AbstractPicoUnit;

class Picometer extends AbstractPicoUnit implements LengthUnitInterface
{
    public static function getSymbol(): string
    {
        return 'pm';
    }
}
