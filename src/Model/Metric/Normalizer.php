<?php

namespace SoureCode\Bundle\Unit\Model\Metric;

final readonly class Normalizer implements NormalizerInterface
{
    public function __construct(
        private ConverterInterface $converter,
        /**
         * @var array<string, class-string<UnitInterface>> $mapping
         */
        private array $mapping = [],
    ) {
    }

    public function normalize(UnitInterface $unit): UnitInterface
    {
        $sourceUnitClass = $this->mapping[$unit::getPrefix()->value] ?? null;

        if (null === $sourceUnitClass) {
            throw new \InvalidArgumentException(\sprintf("Normalization for '%s' is not supported.", $unit::class));
        }

        $shortest = $unit;

        foreach ($this->mapping as $prefix => $targetUnitClass) {
            if ($unit::getPrefix()->value === $prefix) {
                continue;
            }

            $converted = $this->converter->convert($unit, $targetUnitClass::getPrefix());

            if (\strlen($converted->format()) < \strlen($shortest->format())) {
                $shortest = $converted;
            }
        }

        return $shortest;
    }
}
