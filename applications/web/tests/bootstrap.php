<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../');

$environment = new Dotenv(__DIR__ . '/../', '.env.tests');
$environment->load();
$environment->required(['APP_ENV']);
