<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use Dotenv\Dotenv;
use Codeception\Util\Autoload;

Autoload::addNamespace('Page', __DIR__. '/_support/_pages');

$environment = new Dotenv(__DIR__ . '/../');
$environment->load();
$environment->required([
    'DOCTRINE_DEV_MODE',
    'TWIG_DEBUG',
    'MYSQL_USER',
    'MYSQL_PASSWORD',
    'MYSQL_HOST',
]);
