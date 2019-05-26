<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

require __DIR__ . '/../vendor/autoload.php';

use Ewallet\Pimple\EwalletMessagingContainer;
use Ewallet\SymfonyConsole\EwalletApplication;
use Dotenv\Dotenv;

if (!isset($_ENV['APP_ENV'])) {
    // We' re not running the application from the containers
    $environment = new Dotenv(__DIR__ . '/../');
    $environment->load();
    $environment->required([
        'APP_ENV',
        'DB_URL',
        'RABBIT_MQ_USER',
        'RABBIT_MQ_PASSWORD',
        'RABBIT_MQ_HOST'
    ]);
}

$application = new EwalletApplication($container = new EwalletMessagingContainer(
    require __DIR__ . '/../config.php'
));

$application->run();
