<?php

use SoureCode\Bundle\Unit\Doctrine\DBAL\Types\DistanceType;
use SoureCode\Bundle\Unit\Doctrine\DBAL\Types\DurationType;
use Symfony\Component\DependencyInjection\ContainerBuilder;

return static function (ContainerBuilder $containerBuilder) {
    $containerBuilder->prependExtensionConfig('doctrine', [
        'dbal' => [
            'types' => [
                DistanceType::NAME => DistanceType::class,
                DurationType::NAME => DurationType::class,
            ],
        ],
    ]);
};
