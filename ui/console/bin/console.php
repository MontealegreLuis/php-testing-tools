<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Ewallet\Pimple\EwalletConsoleContainer;
use Ewallet\Ui\Console\EwalletApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

if (!isset($_ENV['APP_ENV'])) {
    // We' re not running the application from the containers
    $environment = Dotenv::create(__DIR__ . '/../');
    $environment->load();
    $environment->required(['APP_ENV', 'DB_URL']);
}

$application = new EwalletApplication($container = new EwalletConsoleContainer(
    require __DIR__ . '/../config.php'
));

$application->run($container[InputInterface::class], $container[OutputInterface::class]);
