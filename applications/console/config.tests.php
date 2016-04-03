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
            'driver' => 'pdo_sqlite',
            'path' => __DIR__ . '/var/ewallet.sq3',
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
