<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
require __DIR__ . '/../../../../../../vendor/autoload.php';

$environment = new \Dotenv\Dotenv(__DIR__ . '/../../../../../../');
$environment->load();
$environment->required(['APP_ENV', 'DOCTRINE_DEV_MODE', 'TWIG_DEBUG',]);

$app = new \EwalletApplication\Bridges\Slim\Application(
    require __DIR__ . '/../../../../../../app/config_dev.php'
);
$app->run();
