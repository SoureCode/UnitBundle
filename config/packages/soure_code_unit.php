<?php

use SoureCode\Bundle\Unit\Model\Metric\Length\Centimeter;
use SoureCode\Bundle\Unit\Model\Metric\Length\Decameter;
use SoureCode\Bundle\Unit\Model\Metric\Length\Decimeter;
use SoureCode\Bundle\Unit\Model\Metric\Length\Hectometer;
use SoureCode\Bundle\Unit\Model\Metric\Length\Kilometer;
use SoureCode\Bundle\Unit\Model\Metric\Length\Meter;
use SoureCode\Bundle\Unit\Model\Metric\Length\Micrometer;
use SoureCode\Bundle\Unit\Model\Metric\Length\Millimeter;
use SoureCode\Bundle\Unit\Model\Metric\Length\Nanometer;
use SoureCode\Bundle\Unit\Model\Metric\Length\Picometer;
use SoureCode\Bundle\Unit\Model\Metric\Prefix;
use Symfony\Component\DependencyInjection\ContainerBuilder;

return static function (ContainerBuilder $containerBuilder) {
    $containerBuilder->prependExtensionConfig('soure_code_unit', [
        'units' => [
            'length' => [
                'mapping' => [
                    Prefix::KILO->value => Kilometer::class,
                    Prefix::HECTO->value => Hectometer::class,
                    Prefix::DECA->value => Decameter::class,
                    Prefix::BASE->value => Meter::class,
                    Prefix::DECI->value => Decimeter::class,
                    Prefix::CENTI->value => Centimeter::class,
                    Prefix::MILLI->value => Millimeter::class,
                    Prefix::MICRO->value => Micrometer::class,
                    Prefix::NANO->value => Nanometer::class,
                    Prefix::PICO->value => Picometer::class,
                ],
            ],
        ],
    ]);
};
