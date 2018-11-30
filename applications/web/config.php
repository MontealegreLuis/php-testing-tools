<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
return [
    'settings' => [
        'determineRouteBeforeAppMiddleware' => true,
        'displayErrorDetails' => getenv('APP_ENV') !== 'production',
    ],
    'doctrine' => [
        'mapping_dirs' => [
            __DIR__ . '/vendor/ewallet/domain/src/Ports/Doctrine/Ewallet/Resources/config',
            __DIR__ . '/vendor/ewallet/domain/src/Ports/Doctrine/Application/Resources/config',
        ],
        'dev_mode' => getenv('APP_ENV') !== 'production',
        'proxy_dir' => __DIR__ . '/var/doctrine/proxies',
        'connection' => [
            'url' => str_replace('{{DIR}}', '//' . __DIR__ . '/', getenv('DB_URL')),
        ],
        'types' => [
            'MemberId' => 'Ports\Doctrine\Ewallet\Types\MemberIdType',
        ],
    ],
    'twig' => [
        'options' => [
            'cache' => __DIR__ . '/var/cache/twig',
            'debug' => getenv('APP_ENV') !== 'production',
            'strict_variables' => true,
        ],
        'loader_paths' => [
            __DIR__ . '/src/Twig/Resources/templates',
        ],
    ],
    'forms' => [
        'theme' => 'layouts/bootstrap3.html.twig',
    ],
    'monolog' => [
        'app' => [
            'channel' => 'slim',
            'path' => __DIR__ . '/var/logs/app.log',
        ],
        'ewallet' => [
            'channel' => 'ewallet',
            'path' => __DIR__ . '/var/logs/app.log',
        ]
    ],
];
