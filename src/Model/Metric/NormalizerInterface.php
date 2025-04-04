<?php

namespace SoureCode\Bundle\Unit\Model\Metric;

interface NormalizerInterface
{
    public function normalize(UnitInterface $unit): UnitInterface;
}
