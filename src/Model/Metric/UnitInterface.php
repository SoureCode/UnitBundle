<?php

namespace SoureCode\Bundle\Unit\Model\Metric;

use BcMath\Number;

interface UnitInterface extends \Stringable
{
    public static function getPrefix(): Prefix;

    public static function getSymbol(): string;

    public static function getFactor(): Number;

    public function getValue(): Number;

    public function format(?int $precision = null, ?\RoundingMode $mode = null): string;

    public function round(int $precision = 0, \RoundingMode $mode = \RoundingMode::HalfAwayFromZero): static;
}
