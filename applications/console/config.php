<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
return [
    'doctrine' => [
        'mapping_dirs' => [
            __DIR__ . '/vendor/ewallet/doctrine/src/Doctrine2/Resources/config',
            __DIR__ . '/vendor/hexagonal/doctrine/src/Doctrine2/Resources/config',
        ],
        'dev_mode' => getenv('APP_ENV') !== 'production',
        'proxy_dir' => __DIR__ . '/var/doctrine/proxies',
        'connection' => [
            'url' => getenv('DB_URL'),
        ],
        'types' => [
            'MemberId' => 'Ewallet\Doctrine2\Types\MemberIdType',
        ],
    ],
    'monolog' => [
        'app' => [
            'channel' => 'slim',
        ],
        'ewallet' => [
            'channel' => 'ewallet',
        ]
    ],
];
