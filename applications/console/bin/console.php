<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
require __DIR__ . '/../vendor/autoload.php';

use Ewallet\Pimple\EwalletConsoleContainer;
use Ewallet\SymfonyConsole\EwalletApplication;
use Dotenv\Dotenv;

$environment = new Dotenv(__DIR__ . '/../');
$environment->load();
$environment->required([
    'APP_ENV',
    'MYSQL_USER',
    'MYSQL_PASSWORD',
    'MYSQL_HOST',
]);

$application = new EwalletApplication($container = new EwalletConsoleContainer(
    require __DIR__ . '/../config.php'
));

$application->run(
    $container['ewallet.console_input'],
    $container['ewallet.console_output']
);
