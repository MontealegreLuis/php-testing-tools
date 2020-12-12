<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use Adapters\Doctrine\Ewallet\Types\MemberIdType;

return [
    'doctrine' => [
        'mapping_dirs' => [
            __DIR__ . '/src/Adapters/Doctrine/Ewallet/Resources/config',
            __DIR__ . '/src/Adapters/Doctrine/Application/Resources/config',
        ],
        'dev_mode' => getenv('APP_ENV') !== 'production',
        'proxy_dir' => __DIR__ . '/var/doctrine/proxies',
        'connection' => [
            'dbname' => $_ENV['MYSQL_DATABASE'],
            'user' => $_ENV['MYSQL_USER'],
            'password' => $_ENV['MYSQL_PASSWORD'],
            'host' => $_ENV['MYSQL_HOST'],
            'driver' => 'pdo_mysql',
        ],
        'types' => [
            'MemberId' => MemberIdType::class,
        ],
    ]
];
