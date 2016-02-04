<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
return [
    'doctrine' => [
        'mapping_dirs' => [
            __DIR__ . '/vendor/ewallet/doctrine/src/Doctrine2/Resources/config',
            __DIR__ . '/vendor/hexagonal/doctrine/src/Doctrine2/Resources/config',
        ],
        'dev_mode' => (boolean) getenv('DOCTRINE_DEV_MODE'),
        'proxy_dir' => __DIR__ . '/var/doctrine/proxies',
        'connection' => [
            'driver' => 'pdo_mysql',
            'dbname' => 'ewallet_db',
            'user' => getenv('MYSQL_USER'),
            'password' => getenv('MYSQL_PASSWORD'),
            'host' => getenv('MYSQL_HOST'),
        ],
        'types' => [
            'MemberId' => 'Ewallet\Doctrine2\Types\MemberIdType',
        ],
    ],
    'twig' => [
        'options' => [
            'cache' => __DIR__ . '/var/cache/twig',
            'debug' => (boolean) getenv('TWIG_DEBUG'),
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
            'path' => __DIR__ . '/var/logs/app.log',
        ],
        'ewallet' => [
            'channel' => 'ewallet',
            'path' => __DIR__ . '/var/logs/app.log',
        ]
    ],
];
