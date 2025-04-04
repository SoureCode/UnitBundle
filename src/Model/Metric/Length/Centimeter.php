<?php

namespace SoureCode\Bundle\Unit\Model\Metric\Length;

use SoureCode\Bundle\Unit\Model\Metric\Core\AbstractCentiUnit;

class Centimeter extends AbstractCentiUnit implements LengthUnitInterface
{
    public static function getSymbol(): string
    {
        return 'cm';
    }
}
