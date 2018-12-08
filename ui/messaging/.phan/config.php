<?php
return [
    "directory_list" => [
        __DIR__ . "/../src",
        __DIR__ . '/../vendor/ewallet/definitions',
        __DIR__ . '/../vendor/ewallet/domain',
        __DIR__ . '/../vendor/ewallet/templating',
        __DIR__ . '/../vendor/hexagonal/hexagonal',
        __DIR__ . '/../vendor/mathiasverraes/money',
        __DIR__ . '/../vendor/pimple/pimple',
        __DIR__ . '/../vendor/symfony/console',
        __DIR__ . '/../vendor/zendframework/zend-mail',
    ],
    "exclude_analysis_directory_list" => [
        __DIR__ . "/../src/Pimple/ServiceProviders",
        __DIR__ . '/../vendor',
    ],
];
