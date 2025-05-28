<?php

namespace SoureCode\Bundle\Unit\Normalizer;

use SoureCode\Bundle\Unit\Model\Time\AbstractTimeUnit;
use SoureCode\Bundle\Unit\Model\Time\TimeUnitInterface;

/**
 * @extends AbstractNormalizer<TimeUnitInterface>
 */
class TimeNormalizer extends AbstractNormalizer
{
    public static function normalize(TimeUnitInterface $base): TimeUnitInterface
    {
        /**
         * @var TimeUnitInterface $value
         */
        $value = parent::doNormalize($base, AbstractTimeUnit::CLASSES);

        return $value;
    }
}
