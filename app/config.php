<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
return [
    'doctrine' => [
        'mapping_dirs' => [
            __DIR__ . '/../src/EwalletDoctrineBridge/Resources/config',
        ],
        'dev_mode' => true,
        'proxy_dir' => __DIR__ . '/../var/doctrine/proxies',
        'connection' => [
            'driver' => 'pdo_sqlite',
            'path' => __DIR__ . '/../var/wallet.sqlite',
        ],
        'types' => [
            'uuid' => 'EwalletDoctrineBridge\Types\UuidType',
        ],
    ],
];
