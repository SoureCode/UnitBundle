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

    public function ceil(): static;

    public function floor(): static;

    public function abs(): static;

    public function clone(): static;

    public function compare(self $unit): int;

    public function convert(string $targetUnitClassOrType): self;

    public function normalize(): self;

    public static function create(Number|string|int|float $value, string $unitType): static;
}
