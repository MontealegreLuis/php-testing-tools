<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$environment = new Dotenv(__DIR__ . '/../', '.env.tests');
$environment->load();
$environment->required([
    'DOCTRINE_DEV_MODE',
]);
