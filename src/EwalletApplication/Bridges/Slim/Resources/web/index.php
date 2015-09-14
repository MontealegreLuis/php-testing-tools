<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
require __DIR__ . '/../../../../../../vendor/autoload.php';

$validator = new \Dotenv\Validator(
    ['APP_ENV', 'DOCTRINE_DEV_MODE', 'TWIG_DEBUG',],
    new \Dotenv\Loader(null)
);

$app = new \EwalletApplication\Bridges\Slim\Application(
    require __DIR__ . '/../../../../../../app/config.php'
);
$app->run();
