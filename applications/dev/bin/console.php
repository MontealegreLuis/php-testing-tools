<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Ewallet\SymfonyConsole\Commands\{CreateDatabaseCommand, SeedDatabaseCommand};
use Symfony\Component\Console\Application;

$environment = new Dotenv(__DIR__ . '/../');
$environment->load();
$environment->required([
    'APP_ENV',
    'MYSQL_USER',
    'MYSQL_PASSWORD',
    'MYSQL_HOST',
]);

$options = require __DIR__ . '/../config.php';

$application = new Application();
$application->add(new SeedDatabaseCommand($options));
$application->add(new CreateDatabaseCommand($options));

$application->run();
