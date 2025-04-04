<?php

namespace SoureCode\Bundle\Unit\Model\Metric;

use BcMath\Number;

abstract class AbstractUnit implements UnitInterface
{
    public static ?int $defaultPrecision = null;
    public static \RoundingMode $defaultRoundingMode = \RoundingMode::HalfAwayFromZero;

    protected readonly Number $value;

    public function __construct(Number|string|int|float $value)
    {
        if (\is_float($value)) {
            $value = (string) $value;
        }

        if (\is_string($value)) {
            static $groupings = ['_', ','];
            $value = str_replace($groupings, '', $value);
        }

        if (\is_string($value) && str_contains(strtolower($value), 'e')) {
            $value = self::expandScientificNotation($value);
        }

        if (\is_string($value) || \is_int($value)) {
            try {
                $value = new Number($value);
            } catch (\ValueError $error) {
                throw new \InvalidArgumentException(\sprintf("Invalid value '%s' for %s.", $value, static::class), 0, $error);
            }
        }

        $this->value = self::fixTrailingZeros($value);
    }

    public static function fixTrailingZeros(Number $number): Number
    {
        if (str_contains($number->value, '.')) {
            return new Number(rtrim(rtrim($number->value, '0'), '.'));
        }

        return $number;
    }

    /**
     * @see https://github.com/php/php-src/issues/17876
     */
    public static function expandScientificNotation(string $number): string
    {
        $number = strtolower($number);

        if (!str_contains($number, 'e')) {
            return $number;
        }

        [$mantissa, $exp] = explode('e', $number);
        $exp = (int) $exp;

        // Remove the decimal point from the mantissa.
        if (str_contains($mantissa, '.')) {
            [$intPart, $fracPart] = explode('.', $mantissa);
            $mantissa = $intPart.$fracPart;
            $exp -= \strlen($fracPart);
        }

        // Adjust the number based on the exponent.
        if ($exp > 0) {
            $mantissa .= str_repeat('0', $exp);
        } elseif ($exp < 0) {
            $pos = \strlen($mantissa) + $exp;

            if ($pos <= 0) {
                $mantissa = '0.'.str_repeat('0', abs($pos)).$mantissa;
            } else {
                $mantissa = substr_replace($mantissa, '.', $pos, 0);
            }
        }

        // Strip unnecessary leading zeros unless part of "0.xxx"
        if (str_contains($mantissa, '.')) {
            $mantissa = ltrim($mantissa, '0');
            if (str_starts_with($mantissa, '.')) {
                $mantissa = '0'.$mantissa;
            }
        } else {
            $mantissa = ltrim($mantissa, '0');
            if ('' === $mantissa) {
                $mantissa = '0';
            }
        }

        return $mantissa;
    }

    public function __toString(): string
    {
        return $this->format(static::$defaultPrecision, static::$defaultRoundingMode);
    }

    public function format(?int $precision = null, ?\RoundingMode $mode = null): string
    {
        if (null === $precision) {
            $precision = static::$defaultPrecision ?? null;
        }

        if (null === $mode) {
            $mode = static::$defaultRoundingMode;
        }

        $value = $this->getValue();

        if (null !== $precision) {
            $value = $value->round($precision, $mode);
        }

        return ((string) $value).static::getSymbol();
    }

    public function getValue(): Number
    {
        return $this->value;
    }

    public function round(int $precision = 0, \RoundingMode $mode = \RoundingMode::HalfAwayFromZero): static
    {
        return new static($this->value->round($precision, $mode));
    }
}
