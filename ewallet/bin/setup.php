<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Setup\SetupApplication;

if (!isset($_ENV['APP_ENV'])) {
    // We' re not running the application from the containers
    $environment = new Dotenv(__DIR__ . '/../', '.env');
    $environment->load();
    $environment->required(['APP_ENV']);
}

$application = new SetupApplication(require __DIR__ . '/../setup.php');
$application->run();
