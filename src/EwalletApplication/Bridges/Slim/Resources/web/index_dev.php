<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
require __DIR__ . '/../../../../../../vendor/autoload.php';

$dotEnv = new \Dotenv\Dotenv(__DIR__ . '/../../../../../../');
$dotEnv->load();

$app = new \EwalletApplication\Bridges\Slim\Application(
    require __DIR__ . '/../../../../../../app/config.php'
);
$app->run();
