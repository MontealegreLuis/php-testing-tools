<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Ewallet\SymfonyConsole\Commands\CreateDatabaseCommand;
use Ewallet\SymfonyConsole\Commands\SeedDatabaseCommand;
use Symfony\Component\Console\Application;

$environment = new Dotenv(__DIR__ . '/../');
$environment->load();
$environment->required([
    'APP_ENV',
    'MYSQL_USER',
    'MYSQL_PASSWORD',
    'MYSQL_HOST',
]);

$application = new Application();
$application->add(new SeedDatabaseCommand());
$application->add(new CreateDatabaseCommand());

$application->run();
