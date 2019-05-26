<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use Ports\Doctrine\Ewallet\Types\MemberIdType;

return [
    'doctrine' => [
        'mapping_dirs' => [
            __DIR__ . '/vendor/ewallet/application/src/Ports/Doctrine/Application/Resources/config',
            __DIR__ . '/vendor/ewallet/application/src/Ports/Doctrine/Ewallet/Resources/config',
        ],
        'dev_mode' => getenv('APP_ENV') !== 'production',
        'proxy_dir' => __DIR__ . '/var/doctrine/proxies',
        'connection' => [
            'url' => getenv('DB_URL'),
        ],
        'types' => [
            'MemberId' => MemberIdType::class,
        ],
    ],
    'twig' => [
        'options' => [
            'cache' => __DIR__ . '/var/cache/twig',
            'debug' => getenv('APP_ENV') !== 'production',
            'strict_variables' => true,
        ],
        'loader_paths' => [],
    ],
    'rabbit_mq' => [
        'host' => getenv('RABBIT_MQ_HOST'),
        'port' => 5672,
        'user' => getenv('RABBIT_MQ_USER'),
        'password' => getenv('RABBIT_MQ_PASSWORD'),
    ],
];
