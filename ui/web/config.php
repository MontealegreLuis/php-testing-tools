<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use Adapters\Doctrine\Ewallet\Types\MemberIdType;

return [
    'doctrine' => [
        'mapping_dirs' => [
            __DIR__ . '/vendor/ewallet/application/src/Adapters/Doctrine/Ewallet/Resources/config',
            __DIR__ . '/vendor/ewallet/application/src/Adapters/Doctrine/Application/Resources/config',
        ],
        'dev_mode' => $_ENV['APP_ENV'] !== 'prod',
        'proxy_dir' => __DIR__ . '/var/doctrine/proxies',
        'connection' => [
            'url' => str_replace('{{DIR}}', '//' . __DIR__ . '/', $_ENV['DB_URL']),
            'driver' => $_ENV['DB_DRIVER'],
        ],
        'types' => [
            'MemberId' => MemberIdType::class,
        ],
    ],
    'twig' => [
        'options' => [
            'cache' => __DIR__ . '/var/cache/twig',
            'debug' => $_ENV['APP_ENV'] !== 'prod',
            'strict_variables' => true,
        ],
        'loader_paths' => [
            __DIR__ . '/src/Adapters/Twig/Application/Templating/Resources/templates',
        ],
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
