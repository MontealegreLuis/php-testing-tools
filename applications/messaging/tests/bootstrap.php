<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$environment = new Dotenv(__DIR__ . '/../', '.env.tests');
$environment->load();
$environment->required(['APP_ENV']);
