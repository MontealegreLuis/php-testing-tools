<?php
return [
    "directory_list" => [
        __DIR__ . "/../applications/web/src",
        __DIR__ . "/../applications/web/vendor/container-interop/container-interop",
        __DIR__ . "/../applications/web/vendor/ewallet/application",
        __DIR__ . "/../applications/web/vendor/ewallet/definitions",
        __DIR__ . "/../applications/web/vendor/ewallet/domain",
        __DIR__ . "/../applications/web/vendor/ewallet/responder",
        __DIR__ . "/../applications/web/vendor/hexagonal/hexagonal",
        __DIR__ . "/../applications/web/vendor/monolog/monolog",
        __DIR__ . "/../applications/web/vendor/pimple/pimple",
        __DIR__ . "/../applications/web/vendor/psr/http-message",
        __DIR__ . "/../applications/web/vendor/psr/log",
        __DIR__ . "/../applications/web/vendor/slim/slim",
    ],
    "exclude_analysis_directory_list" => [
        __DIR__ . '/../applications/web/src/Pimple/ServiceProviders',
        __DIR__ . '/../applications/web/vendor',
    ],
];
