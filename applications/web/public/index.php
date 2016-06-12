<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
require __DIR__ . '/../vendor/autoload.php';

use Ewallet\Slim\Application;
use Dotenv\Loader;
use Dotenv\Validator;

$validator = new Validator(
    ['APP_ENV', 'MYSQL_USER', 'MYSQL_PASSWORD', 'MYSQL_HOST'],
    new Loader(null)
);

$app = new Application(
    require __DIR__ . '/../config.php'
);
$app->run();
