<?php

namespace SoureCode\Bundle\Unit\Normalizer;

use SoureCode\Bundle\Unit\Model\Length\AbstractLengthUnit;
use SoureCode\Bundle\Unit\Model\Length\LengthUnitInterface;

/**
 * @extends AbstractNormalizer<LengthUnitInterface>
 */
class LengthNormalizer extends AbstractNormalizer
{
    public static function normalize(LengthUnitInterface $base): LengthUnitInterface
    {
        /**
         * @var LengthUnitInterface $value
         */
        $value = parent::doNormalize($base, AbstractLengthUnit::CLASSES);

        return $value;
    }
}
