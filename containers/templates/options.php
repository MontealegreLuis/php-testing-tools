<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use Dotenv\Dotenv;

if (getenv('ENV') == 'testing') {
    $environment = new Dotenv(__DIR__, '.env.tests');
} else {
    $environment = new Dotenv(__DIR__);
}

$environment->load();
$environment->required(['APP_ENV', 'DB_URL']);

return require __DIR__ . '/config.php';
