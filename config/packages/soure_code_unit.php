<?php

use Symfony\Component\DependencyInjection\ContainerBuilder;

return static function (ContainerBuilder $containerBuilder) {
    $containerBuilder->prependExtensionConfig('soure_code_unit', [
    ]);
};
