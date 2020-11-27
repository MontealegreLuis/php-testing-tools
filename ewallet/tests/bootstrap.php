<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

if (! isset($_ENV['APP_ENV'])) {
    // We' re not running the application from the containers
    $environment = Dotenv::createImmutable(__DIR__ . '/../', '.env.tests');
    $environment->load();
    $environment->required(['APP_ENV']);
}
