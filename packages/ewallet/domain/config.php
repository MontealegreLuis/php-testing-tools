<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
return [
    'doctrine' => [
        'mapping_dirs' => [
            __DIR__ . '/src/Ports/Doctrine/Ewallet/Resources/config',
            __DIR__ . '/src/Ports/Doctrine/Application/Resources/config',
        ],
        'dev_mode' => true,
        'proxy_dir' => __DIR__ . '/var/doctrine/proxies',
        'connection' => [
            'url' => 'sqlite:///' . __DIR__ . '/var/ewallet.sq3',
        ],
        'types' => [
            'MemberId' => 'Ports\Doctrine\Ewallet\Types\MemberIdType',
        ],
    ],
];
