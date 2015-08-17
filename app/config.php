<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
return [
    'doctrine' => [
        'mapping_dirs' => [
            __DIR__ . '/../src/Ewallet/Bridges/Doctrine2/Resources/config',
        ],
        'dev_mode' => true,
        'proxy_dir' => __DIR__ . '/../var/doctrine/proxies',
        'connection' => [
            'driver' => 'pdo_sqlite',
            'path' => __DIR__ . '/../var/wallet.sqlite',
        ],
        'types' => [
            'uuid' => 'Ewallet\Bridges\Doctrine2\Types\UuidType',
        ],
    ],
    'twig' => [
        'options' => [
            'cache' => __DIR__ . '/../var/cache/twig',
            'debug' => true,
            'strict_variables' => true,
        ],
        'loader_paths' => [],
    ],
    'forms' => [
        'theme' => 'layouts/bootstrap3.html.twig',
    ],
    'monolog' => [
        'app' => [
            'channel' => 'slim',
            'path' => __DIR__ . '/../var/logs/app.log',
        ],
        'ewallet' => [
            'channel' => 'ewallet',
            'path' => __DIR__ . '/../var/logs/app.log',
        ]
    ],
    'mail' => [
        'path' => __DIR__ . '/../var/mail',
    ],
];
