<?php

namespace SoureCode\Bundle\Unit\Model;

use BcMath\Number;
use SoureCode\Bundle\Unit\Model\Length\Kilometer;
use SoureCode\Bundle\Unit\Model\Length\LengthUnitInterface;
use SoureCode\Bundle\Unit\Model\Length\Meter;

final class Distance
{
    private LengthUnitInterface $value;

    public function __construct(UnitInterface|LengthUnitInterface|Number|string|float|int $value)
    {
        if ($value instanceof LengthUnitInterface) {
            $this->value = $value->convert(Meter::class);
        } elseif ($value instanceof UnitInterface) {
            throw new \InvalidArgumentException(\sprintf('Unit must be of type %s.', LengthUnitInterface::class));
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

    public function format(): string
    {
        return $this->value->normalize()->format();
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

    public function floor(string $unitClass = Kilometer::class): self
    {
        return new self(
            $this->value->convert($unitClass)
                ->round(0, \RoundingMode::TowardsZero)
                ->convert(Meter::class)
        );
    }

    public function ceil(string $unitClass = Kilometer::class): self
    {
        return new self(
            $this->value->convert($unitClass)
                ->round(0, \RoundingMode::AwayFromZero)
                ->convert(Meter::class)
        );
    }

    public function round(string $unitClass = Kilometer::class): self
    {
        return new self(
            $this->value->convert($unitClass)
                ->round(0)
                ->convert(Meter::class)
        );
    }

    public function getValue(): LengthUnitInterface
    {
        return $this->value;
    }
}
