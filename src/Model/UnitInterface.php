<?php

namespace SoureCode\Bundle\Unit\Model;

use BcMath\Number;

interface UnitInterface extends \Stringable
{
    public static function getUnitType(): string;

    public static function getSymbol(): string;

    public static function getFactor(): Number;

    public static function getCases(): array;

    public static function getMapping(): array;

    public static function getChoices(): array;

    public function getValue(): Number;

    public function format(?int $precision = null, ?\RoundingMode $mode = null): string;

    public function round(int $precision = 0, \RoundingMode $mode = \RoundingMode::HalfAwayFromZero): static;

    /**
     * @param class-string<UnitInterface> $targetUnitClass
     */
    public function convert(string $targetUnitClass): self;

    public function normalize(): self;

    public static function create(Number|string|int|float $value, string $unitType): self;
}
