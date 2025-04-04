<?php

namespace SoureCode\Bundle\Unit\Model\Metric\Length;

use SoureCode\Bundle\Unit\Model\Metric\Core\AbstractMicroUnit;

class Micrometer extends AbstractMicroUnit implements LengthUnitInterface
{
    public static function getSymbol(): string
    {
        return 'μm';
    }
}
