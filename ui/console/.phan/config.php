<?php
return [
    "directory_list" => [
        __DIR__ . "/../src",
        __DIR__ . "/../vendor/ewallet/application",
        __DIR__ . "/../vendor/ewallet/definitions",
        __DIR__ . "/../vendor/ewallet/domain",
        __DIR__ . "/../vendor/hexagonal/hexagonal",
        __DIR__ . "/../vendor/pimple/pimple",
        __DIR__ . "/../vendor/symfony/console",
    ],
    "exclude_analysis_directory_list" => [
        __DIR__ . '/../src/Pimple/ServiceProviders',
        __DIR__ . '/../vendor',
    ],
];
