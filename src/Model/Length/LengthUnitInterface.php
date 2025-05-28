<?php

namespace SoureCode\Bundle\Unit\Model\Length;

use SoureCode\Bundle\Unit\Model\UnitInterface;

interface LengthUnitInterface extends UnitInterface
{
    /**
     * @phpstan-template T of LengthUnitInterface
     *
     * @phpstan-param class-string<T> $className
     *
     * @phpstan-return T
     */
    public function convert(string $className): self;

    public function normalize(): self;
}
