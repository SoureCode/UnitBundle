<?php

namespace SoureCode\Bundle\Unit\Model\Metric;

/**
 * @template T of UnitInterface
 */
interface ConverterInterface
{
    /**
     * @psalm-param T $unit
     *
     * @psalm-return T
     */
    public function convert(UnitInterface $unit, Prefix $target): UnitInterface;
}
