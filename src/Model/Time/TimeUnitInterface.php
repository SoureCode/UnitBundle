<?php

namespace SoureCode\Bundle\Unit\Model\Time;

use SoureCode\Bundle\Unit\Model\UnitInterface;

interface TimeUnitInterface extends UnitInterface
{
    /**
     * @phpstan-template T of TimeUnitInterface
     *
     * @phpstan-param class-string<T> $className
     *
     * @phpstan-return T
     */
    public function convert(string $className): self;

    public function normalize(): self;
}
