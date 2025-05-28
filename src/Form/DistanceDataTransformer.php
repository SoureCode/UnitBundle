<?php

namespace SoureCode\Bundle\Unit\Form;

use SoureCode\Bundle\Unit\Factory\LengthFactory;
use SoureCode\Bundle\Unit\Model\Distance;
use SoureCode\Bundle\Unit\Model\Length\LengthUnitType;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * @template-implements DataTransformerInterface<?Distance, array{value: ?string, unit: ?string}|null>
 */
class DistanceDataTransformer implements DataTransformerInterface
{
    public function transform(mixed $value): ?array
    {
        if (null === $value) {
            return null;
        }

        $normalized = $value->getValue()->normalize();

        return [
            'value' => (string) $normalized->getValue(),
            'unit' => $normalized::getUnitType(),
        ];
    }

    /**
     * Converts the data from the form to the model.
     */
    public function reverseTransform(mixed $value): ?Distance
    {
        if (null === $value) {
            return null;
        }

        if (null === $value['unit'] || null === $value['value']) {
            return null;
        }

        $type = LengthUnitType::from($value['unit']);
        $className = $type->toClassName();

        return Distance::create(LengthFactory::create($value['value'], $className));
    }
}
