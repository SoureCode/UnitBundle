<?php

namespace SoureCode\Bundle\Unit\Normalizer;

use BcMath\Number;
use SoureCode\Bundle\Unit\Model\UnitInterface;

/**
 * @template T of UnitInterface
 */
class AbstractNormalizer
{
    /**
     * @phpstan-param T $value
     * @phpstan-param list<class-string<T>> $classNames
     *
     * @phpstan-return UnitInterface
     */
    protected static function doNormalize(UnitInterface $value, array $classNames): UnitInterface
    {
        if (0 === $value->getValue()->compare(new Number(0))) {
            return new ($value::class)(0);
        }

        /**
         * @var list<array{unit: UnitInterface, value: float}> $candidates
         */
        $candidates = [];

        foreach ($classNames as $className) {
            $converted = $value->convert($className);
            $absValue = abs((float) $converted->getValue()->value);

            // Collect candidates where the number is at least 1.
            if ($absValue >= 1) {
                $candidates[] = [
                    'unit' => $converted,
                    'value' => $absValue,
                ];
            }
        }

        // >= 1
        if (0 !== \count($candidates)) {
            usort($candidates, static function ($a, $b) {
                return $a['value'] <=> $b['value'];
            });

            return $candidates[0]['unit'];
        }

        // < 1
        $bestCandidate = null;

        foreach ($classNames as $className) {
            $converted = $value->convert($className);
            $absValue = abs((float) $converted->getValue()->value);

            if (null === $bestCandidate || $absValue > $bestCandidate['value']) {
                $bestCandidate = ['unit' => $converted, 'value' => $absValue];
            }
        }

        return $bestCandidate['unit'] ?? $value;
    }
}
