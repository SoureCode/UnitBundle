<?php

namespace SoureCode\Bundle\Unit\Model\Metric;

use BcMath\Number;

/**
 * @template T of UnitInterface
 */
interface FactoryInterface
{
    /**
     * @psalm-return T
     */
    public function create(Number|string|int|float $value, Prefix $prefix): UnitInterface;
}
