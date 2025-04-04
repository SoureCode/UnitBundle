<?php

use SoureCode\Bundle\Unit\Doctrine\DBAL\Types\LengthType;
use Symfony\Component\DependencyInjection\ContainerBuilder;

return static function (ContainerBuilder $containerBuilder) {
    $containerBuilder->prependExtensionConfig('doctrine', [
        'dbal' => [
            'types' => [
                LengthType::NAME => LengthType::class,
            ],
        ],
    ]);
};
