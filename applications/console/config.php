<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
return [
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
    'monolog' => [
        'app' => [
            'channel' => 'slim',
        ],
        'ewallet' => [
            'channel' => 'ewallet',
        ]
    ],
];
