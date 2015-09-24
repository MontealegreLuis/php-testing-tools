<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
require __DIR__ . '/../../../../../../vendor/autoload.php';

use EwalletApplication\Bridges\Slim\Application;
use Dotenv\Loader;
use Dotenv\Validator;

$validator = new Validator(
    ['APP_ENV', 'DOCTRINE_DEV_MODE', 'TWIG_DEBUG', 'SMTP_HOST', 'SMTP_PORT'],
    new Loader(null)
);

$app = new Application(
    require __DIR__ . '/../../../../../../app/config.php'
);
$app->run();
