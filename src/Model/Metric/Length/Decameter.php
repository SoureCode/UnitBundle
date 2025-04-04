<?php

namespace SoureCode\Bundle\Unit\Model\Metric\Length;

use SoureCode\Bundle\Unit\Model\Metric\Core\AbstractDecaUnit;

class Decameter extends AbstractDecaUnit implements LengthUnitInterface
{
    public static function getSymbol(): string
    {
        return 'dam';
    }
}
