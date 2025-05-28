<?php

namespace SoureCode\Bundle\Unit\Model;

use BcMath\Number;
use SoureCode\Bundle\Unit\Model\Length\Kilometer;
use SoureCode\Bundle\Unit\Model\Length\LengthUnitInterface;
use SoureCode\Bundle\Unit\Model\Length\Meter;

final class Distance implements \Stringable
{
    private LengthUnitInterface $value;

    public function __construct(LengthUnitInterface|Number|string|float|int $value)
    {
        if ($value instanceof LengthUnitInterface) {
            $this->value = $value->convert(Meter::class);
        } else {
            $this->value = new Meter($value);
        }
    }

    public static function zero(): self
    {
        return new self(new Meter(0));
    }

    public static function create(LengthUnitInterface|Number|string|float|int $value): self
    {
        return new self($value);
    }

    public function sub(self|LengthUnitInterface|Number|string|float|int $value): self
    {
        if ($value instanceof LengthUnitInterface) {
            $value = $value->convert(Meter::class);

            return new self($this->value->getValue()->sub($value->getValue()));
        }

        if ($value instanceof self) {
            return new self($this->value->getValue()->sub($value->value->getValue()));
        }

        $meter = new Meter($value);

        return new self($this->value->getValue()->sub($meter->getValue()));
    }

    public function getValue(): LengthUnitInterface
    {
        return $this->value->clone();
    }

    public function add(self|LengthUnitInterface|Number|string|float|int $value): self
    {
        if ($value instanceof LengthUnitInterface) {
            $value = $value->convert(Meter::class);

            return new self($this->value->getValue()->add($value->getValue()));
        }

        if ($value instanceof self) {
            return new self($this->value->getValue()->add($value->value->getValue()));
        }

        $meter = new Meter($value);

        return new self($this->value->getValue()->add($meter->getValue()));
    }

    /**
     * @param class-string<LengthUnitInterface> $className
     */
    public function floor(string $className = Kilometer::class): self
    {
        return new self(
            $this->value->convert($className)
                ->floor()
                ->convert(Meter::class)
        );
    }

    /**
     * @param class-string<LengthUnitInterface> $className
     */
    public function ceil(string $className = Kilometer::class): self
    {
        return new self(
            $this->value->convert($className)
                ->ceil()
                ->convert(Meter::class)
        );
    }

    /**
     * @param class-string<LengthUnitInterface> $className
     */
    public function round(string $className = Kilometer::class): self
    {
        return new self(
            $this->value->convert($className)
                ->round()
                ->convert(Meter::class)
        );
    }

    public function compare(self $distance): int
    {
        return $this->value->compare($distance->getValue());
    }

    public function __toString()
    {
        return $this->format();
    }

    public function format(): string
    {
        return $this->value->normalize()->format();
    }
}
