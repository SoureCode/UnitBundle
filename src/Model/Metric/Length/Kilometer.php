<?php

namespace SoureCode\Bundle\Unit\Model\Metric\Length;

use SoureCode\Bundle\Unit\Model\Metric\Core\AbstractKiloUnit;

class Kilometer extends AbstractKiloUnit implements LengthUnitInterface
{
    public static function getSymbol(): string
    {
        return 'km';
    }
}
