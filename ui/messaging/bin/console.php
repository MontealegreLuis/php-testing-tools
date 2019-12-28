<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Ewallet\Pimple\EwalletMessagingContainer;
use Ewallet\Ui\Console\EwalletApplication;

if (! isset($_ENV['APP_ENV'])) {
    // We' re not running the application from the containers
    $environment = Dotenv::create(__DIR__ . '/../');
    $environment->load();
    $environment->required([
        'APP_ENV',
        'DB_URL',
        'RABBIT_MQ_USER',
        'RABBIT_MQ_PASSWORD',
        'RABBIT_MQ_HOST',
    ]);
}

$application = new EwalletApplication($container = new EwalletMessagingContainer(
    require __DIR__ . '/../config.php'
));

$application->run();
