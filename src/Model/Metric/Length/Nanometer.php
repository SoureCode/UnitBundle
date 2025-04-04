<?php

namespace SoureCode\Bundle\Unit\Model\Metric\Length;

use SoureCode\Bundle\Unit\Model\Metric\Core\AbstractNanoUnit;

class Nanometer extends AbstractNanoUnit implements LengthUnitInterface
{
    public static function getSymbol(): string
    {
        return 'nm';
    }
}
