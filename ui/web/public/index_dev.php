<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use UI\Slim\Application;

if (isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] === 'production') {
    http_response_code(404);
    die;
}

$environment = Dotenv::create(__DIR__ . '/../', '.env.tests');
$environment->load();
$environment->required(['APP_ENV', 'DB_URL', 'PDO_DRIVER']);

$app = new Application(require __DIR__ . '/../config.php');
$app->run();
