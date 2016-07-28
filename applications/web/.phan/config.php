<?php
return [
    "directory_list" => [
        __DIR__ . "/../src",
        __DIR__ . "/../vendor/container-interop/container-interop",
        __DIR__ . "/../vendor/ewallet/application",
        __DIR__ . "/../vendor/ewallet/definitions",
        __DIR__ . "/../vendor/ewallet/domain",
        __DIR__ . "/../vendor/ewallet/responder",
        __DIR__ . "/../vendor/hexagonal/hexagonal",
        __DIR__ . "/../vendor/monolog/monolog",
        __DIR__ . "/../vendor/pimple/pimple",
        __DIR__ . "/../vendor/psr/http-message",
        __DIR__ . "/../vendor/psr/log",
        __DIR__ . "/../vendor/slim/slim",
    ],
    "exclude_analysis_directory_list" => [
        __DIR__ . '/../src/Pimple/ServiceProviders',
        __DIR__ . '/../vendor',
    ],
];
